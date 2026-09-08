<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HostelRoom extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'hostel_id',
        'room_number',
        'block_name',
        'floor',
        'room_type',
        'capacity',
        'allocated_count',
        'fee_amount',
    ];

    protected function casts(): array
    {
        return [
            'floor' => 'integer',
            'capacity' => 'integer',
            'allocated_count' => 'integer',
            'fee_amount' => 'decimal:2',
        ];
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function bedSpaces(): HasMany
    {
        return $this->hasMany(BedSpace::class);
    }

    public function maintenanceTickets(): HasMany
    {
        return $this->hasMany(HostelMaintenanceTicket::class);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->allocated_count >= $this->capacity;
    }
}
