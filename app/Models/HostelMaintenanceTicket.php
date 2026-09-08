<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostelMaintenanceTicket extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'student_id',
        'hostel_room_id',
        'ticket_number',
        'category',
        'priority',
        'description',
        'status',
        'resolution_notes',
        'resolved_by_staff_id',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(HostelRoom::class, 'hostel_room_id');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'resolved_by_staff_id');
    }
}
