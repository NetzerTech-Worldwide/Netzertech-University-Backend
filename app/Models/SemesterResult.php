<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SemesterResult extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'student_id',
        'academic_session',
        'semester',
        'level',
        'total_credits_registered',
        'total_credits_earned',
        'total_grade_points',
        'gpa',
        'cumulative_credits_registered',
        'cumulative_credits_earned',
        'cumulative_grade_points',
        'cgpa',
        'standing',
    ];

    protected function casts(): array
    {
        return [
            'total_credits_registered' => 'integer',
            'total_credits_earned' => 'integer',
            'total_grade_points' => 'decimal:2',
            'gpa' => 'decimal:2',
            'cumulative_credits_registered' => 'integer',
            'cumulative_credits_earned' => 'integer',
            'cumulative_grade_points' => 'decimal:2',
            'cgpa' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
