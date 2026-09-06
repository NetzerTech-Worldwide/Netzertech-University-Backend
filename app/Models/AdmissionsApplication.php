<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdmissionsApplication extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'user_id',
        'application_no',
        'first_choice_programme_id',
        'second_choice_programme_id',
        'status',
        'app_fee_paid',
        'screening_score',
        'offer_accepted',
        'acceptance_fee_paid',
        'profile_complete',
        'documents_verified',
        'matric_number_issued',
        'uploaded_documents',
        'steps_completed',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'app_fee_paid' => 'boolean',
            'offer_accepted' => 'boolean',
            'acceptance_fee_paid' => 'boolean',
            'profile_complete' => 'boolean',
            'documents_verified' => 'boolean',
            'uploaded_documents' => 'array',
            'steps_completed' => 'array',
            'submitted_at' => 'datetime',
            'screening_score' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function firstChoiceProgramme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'first_choice_programme_id');
    }

    public function secondChoiceProgramme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'second_choice_programme_id');
    }

    public function jambRecord(): HasOne
    {
        return $this->hasOne(JambRecord::class, 'application_id');
    }

    public function olevelResults(): HasMany
    {
        return $this->hasMany(OLevelResult::class, 'application_id');
    }

    public function postUtmeSlot(): HasOne
    {
        return $this->hasOne(PostUtmeSlot::class, 'application_id');
    }
}
