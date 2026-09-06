<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'department_id',
        'code',
        'title',
        'credit_units',
        'level',
        'semester',
        'is_elective',
        'prerequisite_course_id',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'credit_units' => 'integer',
            'is_elective' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function prerequisite(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'prerequisite_course_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(CourseMaterial::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function cbtExams(): HasMany
    {
        return $this->hasMany(CbtExam::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(CourseResult::class);
    }
}
