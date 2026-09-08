<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'user_id',
        'invoice_number',
        'title',
        'fee_type',
        'academic_session',
        'semester',
        'base_amount',
        'platform_fee',
        'total_amount',
        'amount_paid',
        'status',
        'due_date',
        'installment_allowed',
        'installment_plan',
    ];

    protected function casts(): array
    {
        return [
            'base_amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'installment_allowed' => 'boolean',
            'installment_plan' => 'array',
            'due_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getOutstandingBalanceAttribute(): float
    {
        return max(0.00, (float) $this->total_amount - (float) $this->amount_paid);
    }
}
