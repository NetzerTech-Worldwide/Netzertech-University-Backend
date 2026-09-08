<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'invoice_id',
        'user_id',
        'transaction_reference',
        'amount',
        'platform_fee',
        'university_amount',
        'gateway',
        'gateway_reference',
        'status',
        'receipt_number',
        'split_code',
        'raw_webhook_payload',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'university_amount' => 'decimal:2',
            'raw_webhook_payload' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
