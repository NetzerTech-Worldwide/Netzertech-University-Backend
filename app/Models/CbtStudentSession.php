<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CbtStudentSession extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'cbt_exam_id',
        'student_id',
        'started_at',
        'expires_at',
        'submitted_at',
        'answers',
        'score_obtained',
        'percentage',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'expires_at' => 'datetime',
            'submitted_at' => 'datetime',
            'answers' => 'array',
            'score_obtained' => 'decimal:2',
            'percentage' => 'decimal:2',
        ];
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(CbtExam::class, 'cbt_exam_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
