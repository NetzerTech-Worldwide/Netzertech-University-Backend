<?php

namespace App\Services\Facilities;

use App\Models\BedSpace;
use App\Models\HostelAllocation;
use App\Models\HostelMaintenanceTicket;
use App\Models\HostelRoom;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class HostelAllocationService
{
    /**
     * Reserve a bed space atomically with pessimistic locking to prevent race conditions.
     */
    public function reserveBedSpace(Student $student, int $bedSpaceId, string $session): array
    {
        return DB::transaction(function () use ($student, $bedSpaceId, $session) {
            // Lock bed space row against concurrent bookings
            $bedSpace = BedSpace::with('room.hostel')->where('id', $bedSpaceId)->lockForUpdate()->firstOrFail();

            if ($bedSpace->status !== 'available') {
                throw new InvalidArgumentException('This bed space is no longer available. Please select an available bed.');
            }

            // Verify student does not already hold an active allocation for this session
            $existing = HostelAllocation::where('student_id', $student->id)
                ->where('academic_session', $session)
                ->whereIn('status', ['allocated', 'confirmed', 'checked_in'])
                ->first();

            if ($existing) {
                throw new InvalidArgumentException("You already have an active hostel bed allocation for the {$session} session.");
            }

            $room = $bedSpace->room;
            $hostel = $room->hostel;

            // Enforce gender policy
            $studentGender = strtolower($student->profile?->gender ?? 'male');
            $hostelGender = strtolower($hostel->gender ?? 'mixed');

            if ($hostelGender !== 'mixed' && $hostelGender !== $studentGender) {
                throw new InvalidArgumentException("Gender restriction: {$hostel->name} is reserved for {$hostelGender} residents.");
            }

            // Enforce room capacity
            if ($room->allocated_count >= $room->capacity) {
                throw new InvalidArgumentException('This room is already fully occupied.');
            }

            // Reserve bed and increment room occupancy
            $bedSpace->update(['status' => 'occupied']);
            $room->increment('allocated_count');

            $ref = 'HST-' . now()->format('Y') . '-' . strtoupper(Str::random(6));

            $allocation = HostelAllocation::create([
                'university_id' => $student->university_id,
                'student_id' => $student->id,
                'bed_space_id' => $bedSpace->id,
                'academic_session' => $session,
                'allocation_ref' => $ref,
                'status' => 'allocated',
                'rules_agreed' => false,
                'check_in_date' => now()->addDays(3)->toDateString(),
                'check_out_date' => now()->addMonths(9)->toDateString(),
            ]);

            // Auto-generate linked hostel fee invoice if not already existing
            $existingInvoice = Invoice::where('user_id', $student->user_id)
                ->where('fee_type', 'hostel')
                ->where('academic_session', $session)
                ->first();

            if (!$existingInvoice && $room->fee_amount > 0) {
                $platformFee = (float) ($student->university->platform_fee_amount ?? 1500.00);
                $invoice = Invoice::create([
                    'university_id' => $student->university_id,
                    'user_id' => $student->user_id,
                    'invoice_number' => 'INV-HST-' . strtoupper(Str::random(6)),
                    'title' => "Hostel Accommodation Fee - {$hostel->name} ({$room->room_number})",
                    'fee_type' => 'hostel',
                    'academic_session' => $session,
                    'base_amount' => $room->fee_amount,
                    'platform_fee' => $platformFee,
                    'total_amount' => $room->fee_amount + $platformFee,
                    'amount_paid' => 0.00,
                    'status' => 'unpaid',
                    'due_date' => now()->addDays(7)->toDateString(),
                ]);

                InvoiceItem::create([
                    'university_id' => $student->university_id,
                    'invoice_id' => $invoice->id,
                    'name' => "Room Allocation {$hostel->name} - {$room->room_number} ({$bedSpace->bed_label})",
                    'amount' => $room->fee_amount,
                    'is_paid' => false,
                ]);
            }

            return [
                'allocation' => $allocation->fresh(['bedSpace.room.hostel']),
                'hostel' => $hostel,
                'room' => $room,
                'bed' => $bedSpace,
            ];
        });
    }

    /**
     * Student agrees to hostel code of conduct and rules.
     */
    public function signAgreement(HostelAllocation $allocation): HostelAllocation
    {
        $allocation->update([
            'rules_agreed' => true,
            'rules_agreed_at' => now(),
            'status' => 'confirmed',
        ]);

        return $allocation;
    }

    /**
     * Create maintenance ticket for a hostel room.
     */
    public function createMaintenanceTicket(Student $student, int $roomId, array $data): HostelMaintenanceTicket
    {
        $ticketNo = 'TKT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

        return HostelMaintenanceTicket::create([
            'university_id' => $student->university_id,
            'student_id' => $student->id,
            'hostel_room_id' => $roomId,
            'ticket_number' => $ticketNo,
            'category' => $data['category'] ?? 'other',
            'priority' => $data['priority'] ?? 'medium',
            'description' => $data['description'],
            'status' => 'open',
        ]);
    }
}
