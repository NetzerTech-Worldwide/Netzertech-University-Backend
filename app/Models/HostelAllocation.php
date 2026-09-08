<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostelAllocation extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'student_id',
        'bed_space_id',
        'academic_session',
        'allocation_ref',
        'status',
        'rules_agreed',
        'rules_agreed_at',
        'check_in_date',
        'check_out_date',
    ];

    protected function casts(): array
    {
        return [
            'rules_agreed' => 'boolean',
            'rules_agreed_at' => 'datetime',
            'check_in_date' => 'date',
            'check_out_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function bedSpace(): BelongsTo
    {
        return $this->belongsTo(BedSpace::class);
    }
}
