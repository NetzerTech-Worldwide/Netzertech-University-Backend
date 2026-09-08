<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BedSpace extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'hostel_room_id',
        'bed_label',
        'status',
        'lock_token',
        'locked_until',
    ];

    protected function casts(): array
    {
        return [
            'locked_until' => 'datetime',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(HostelRoom::class, 'hostel_room_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(HostelAllocation::class);
    }

    public function currentAllocation(): ?HostelAllocation
    {
        return $this->allocations()->whereIn('status', ['allocated', 'confirmed', 'checked_in'])->latest()->first();
    }
}
