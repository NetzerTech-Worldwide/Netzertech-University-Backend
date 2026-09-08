<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovalRequest extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'student_id',
        'request_ref',
        'type',
        'title',
        'reason',
        'supporting_document_url',
        'total_levels',
        'current_level',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'total_levels' => 'integer',
            'current_level' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(ApprovalStep::class)->orderBy('level_number');
    }

    public function currentStep(): ?ApprovalStep
    {
        return $this->steps()->where('level_number', $this->current_level)->first();
    }
}
