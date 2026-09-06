<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CbtExam extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'course_id',
        'created_by_staff_id',
        'title',
        'instructions',
        'duration_minutes',
        'total_marks',
        'pass_percentage',
        'start_time',
        'end_time',
        'shuffle_questions',
        'shuffle_options',
        'show_result_immediately',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'total_marks' => 'decimal:2',
            'pass_percentage' => 'decimal:2',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_result_immediately' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by_staff_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(CbtQuestion::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(CbtStudentSession::class);
    }
}
