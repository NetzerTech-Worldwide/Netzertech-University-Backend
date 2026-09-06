<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'faculty_id',
        'name',
        'code',
        'description',
        'hod_staff_id',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function programmes(): HasMany
    {
        return $this->hasMany(Programme::class);
    }

    public function hod(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'hod_staff_id');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
