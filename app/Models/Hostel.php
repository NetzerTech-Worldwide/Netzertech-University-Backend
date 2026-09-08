<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hostel extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'name',
        'code',
        'gender',
        'campus_location',
        'master_staff_id',
        'capacity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(HostelRoom::class);
    }

    public function master(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'master_staff_id');
    }
}
