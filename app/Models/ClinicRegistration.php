<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicRegistration extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'student_id',
        'hospital_number',
        'blood_group',
        'genotype',
        'allergies',
        'chronic_conditions',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'is_cleared',
        'registered_at',
    ];

    protected function casts(): array
    {
        return [
            'is_cleared' => 'boolean',
            'registered_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
