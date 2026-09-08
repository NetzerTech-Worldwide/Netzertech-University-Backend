<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalStep extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'approval_request_id',
        'assigned_staff_id',
        'required_role',
        'level_number',
        'action',
        'comments',
        'acted_by_user_id',
        'acted_at',
    ];

    protected function casts(): array
    {
        return [
            'level_number' => 'integer',
            'acted_at' => 'datetime',
        ];
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(ApprovalRequest::class, 'approval_request_id');
    }

    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

    public function actedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acted_by_user_id');
    }
}
