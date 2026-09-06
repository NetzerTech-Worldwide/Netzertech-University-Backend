<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'name',
        'code',
        'description',
        'dean_staff_id',
    ];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function dean(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'dean_staff_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
