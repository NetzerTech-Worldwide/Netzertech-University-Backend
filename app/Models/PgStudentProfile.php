<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PgStudentProfile extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'student_id',
        'programme_type',
        'research_title',
        'primary_supervisor_staff_id',
        'co_supervisor_staff_id',
        'current_stage',
        'expected_graduation_date',
        'risk_score',
        'risk_level',
    ];

    protected function casts(): array
    {
        return [
            'expected_graduation_date' => 'date',
            'risk_score' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function primarySupervisor(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'primary_supervisor_staff_id');
    }

    public function coSupervisor(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'co_supervisor_staff_id');
    }
}
