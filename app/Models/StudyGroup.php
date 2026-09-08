<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyGroup extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'course_id',
        'creator_student_id',
        'name',
        'description',
        'max_members',
        'meeting_schedule',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'max_members' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'creator_student_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(StudyGroupMember::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(StudyGroupMessage::class)->oldest();
    }
}
