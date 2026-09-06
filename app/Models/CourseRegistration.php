<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseRegistration extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'student_id',
        'academic_session',
        'semester',
        'total_credits',
        'status',
        'approved_by_staff_id',
        'rejection_reason',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'total_credits' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'approved_by_staff_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CourseRegistrationItem::class);
    }
}
