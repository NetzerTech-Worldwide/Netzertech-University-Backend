<?php

namespace App\Http\Controllers\Api\V1\Facilities;

use App\Http\Controllers\Controller;
use App\Models\BedSpace;
use App\Models\Hostel;
use App\Models\HostelAllocation;
use App\Models\HostelMaintenanceTicket;
use App\Models\HostelRoom;
use App\Services\Facilities\HostelAllocationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class HostelController extends Controller
{
    use ApiResponse;

    /**
     * List all hostels with availability and gender filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Hostel::where('is_active', true)->with('master');

        if ($gender = $request->query('gender')) {
            $query->where(function ($q) use ($gender) {
                $q->where('gender', $gender)->orWhere('gender', 'mixed');
            });
        }

        $hostels = $query->get()->map(function ($hostel) {
            $totalBeds = BedSpace::whereHas('room', fn($q) => $q->where('hostel_id', $hostel->id))->count();
            $availableBeds = BedSpace::whereHas('room', fn($q) => $q->where('hostel_id', $hostel->id))
                ->where('status', 'available')
                ->count();

            return [
                'id' => $hostel->id,
                'name' => $hostel->name,
                'code' => $hostel->code,
                'gender' => $hostel->gender,
                'campus_location' => $hostel->campus_location,
                'master_name' => $hostel->master?->title . ' ' . $hostel->master?->user?->name,
                'capacity' => $hostel->capacity,
                'total_beds' => $totalBeds,
                'available_beds' => $availableBeds,
            ];
        });

        return $this->success($hostels, 'Hostels retrieved successfully.');
    }

    /**
     * Get rooms and bed-spaces in a specific hostel.
     */
    public function getRooms(int $id): JsonResponse
    {
        $hostel = Hostel::findOrFail($id);
        $rooms = HostelRoom::where('hostel_id', $hostel->id)
            ->with(['bedSpaces'])
            ->orderBy('room_number')
            ->get();

        return $this->success([
            'hostel' => $hostel,
            'rooms' => $rooms,
        ], 'Hostel rooms retrieved successfully.');
    }

    /**
     * Concurrency-safe bed space reservation.
     */
    public function reserveBed(Request $request, HostelAllocationService $hostelService): JsonResponse
    {
        $request->validate([
            'bed_space_id' => 'required|exists:bed_spaces,id',
            'academic_session' => 'nullable|string|max:20',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Only matriculated students can reserve hostel accommodation.', HttpStatus::HTTP_FORBIDDEN);
        }

        $session = $request->input('academic_session', $student->academic_session ?? '2025/2026');

        try {
            $result = $hostelService->reserveBedSpace($student, (int) $request->input('bed_space_id'), $session);
            return $this->success($result, 'Bed space reserved successfully.', HttpStatus::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * View current authenticated student's bed allocation and roommates.
     */
    public function myAllocation(Request $request): JsonResponse
    {
        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student record not found.', HttpStatus::HTTP_NOT_FOUND);
        }

        $allocation = HostelAllocation::where('student_id', $student->id)
            ->whereIn('status', ['allocated', 'confirmed', 'checked_in'])
            ->with(['bedSpace.room.hostel'])
            ->latest()
            ->first();

        if (!$allocation) {
            return $this->error('No active hostel allocation found for this student.', HttpStatus::HTTP_NOT_FOUND);
        }

        $room = $allocation->bedSpace->room;
        
        // Find roommates in the same room
        $roommates = HostelAllocation::whereHas('bedSpace', fn($q) => $q->where('hostel_room_id', $room->id))
            ->where('student_id', '!=', $student->id)
            ->whereIn('status', ['allocated', 'confirmed', 'checked_in'])
            ->with(['student.user', 'student.department', 'bedSpace'])
            ->get()
            ->map(function ($alloc) {
                return [
                    'name' => $alloc->student->user->name,
                    'matric_number' => $alloc->student->matric_number,
                    'department' => $alloc->student->department?->name,
                    'level' => $alloc->student->level,
                    'bed_label' => $alloc->bedSpace->bed_label,
                    'status' => $alloc->status,
                ];
            });

        return $this->success([
            'allocation' => $allocation,
            'hostel' => $allocation->bedSpace->room->hostel,
            'room' => $room,
            'assigned_bed' => $allocation->bedSpace->bed_label,
            'roommates' => $roommates,
            'rules_agreed' => $allocation->rules_agreed,
        ], 'Hostel allocation retrieved successfully.');
    }

    /**
     * Agree to hostel regulations and code of conduct.
     */
    public function signAgreement(Request $request, HostelAllocationService $hostelService): JsonResponse
    {
        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student record not found.', HttpStatus::HTTP_NOT_FOUND);
        }

        $allocation = HostelAllocation::where('student_id', $student->id)
            ->whereIn('status', ['allocated', 'confirmed'])
            ->latest()
            ->firstOrFail();

        $updated = $hostelService->signAgreement($allocation);

        return $this->success($updated, 'Hostel regulations agreement signed successfully.');
    }

    /**
     * View maintenance tickets.
     */
    public function maintenanceTickets(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = HostelMaintenanceTicket::with(['room.hostel', 'student.user', 'resolver.user']);

        if ($user->hasRole('student')) {
            $query->where('student_id', $user->student?->id);
        }

        $tickets = $query->latest()->get();

        return $this->success($tickets, 'Maintenance tickets retrieved successfully.');
    }

    /**
     * Submit a maintenance report.
     */
    public function submitMaintenance(Request $request, HostelAllocationService $hostelService): JsonResponse
    {
        $request->validate([
            'hostel_room_id' => 'required|exists:hostel_rooms,id',
            'category' => 'required|string|in:plumbing,electrical,carpentry,cleanliness,other',
            'priority' => 'required|string|in:low,medium,high,emergency',
            'description' => 'required|string|max:1000',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_FORBIDDEN);
        }

        $ticket = $hostelService->createMaintenanceTicket(
            $student,
            (int) $request->input('hostel_room_id'),
            $request->only(['category', 'priority', 'description'])
        );

        return $this->success($ticket, 'Maintenance ticket submitted successfully.', HttpStatus::HTTP_CREATED);
    }

    /**
     * Resolve a maintenance ticket (Hostel Master / Staff).
     */
    public function resolveMaintenance(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'resolution_notes' => 'required|string|max:1000',
        ]);

        $ticket = HostelMaintenanceTicket::findOrFail($id);
        $staff = $request->user()->staff;

        $ticket->update([
            'status' => 'resolved',
            'resolution_notes' => $request->input('resolution_notes'),
            'resolved_by_staff_id' => $staff?->id,
        ]);

        return $this->success($ticket, 'Maintenance ticket marked as resolved.');
    }
}
