<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicAppointment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'student_id',
        'doctor_staff_id',
        'visit_date',
        'symptoms',
        'diagnosis',
        'prescription',
        'doctor_notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'doctor_staff_id');
    }
}
