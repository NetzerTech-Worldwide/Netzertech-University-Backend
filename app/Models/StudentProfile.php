<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'dob',
        'gender',
        'state_of_origin',
        'lga',
        'nationality',
        'address',
        'city',
        'state',
        'father_name',
        'father_phone',
        'father_occupation',
        'mother_name',
        'mother_phone',
        'mother_occupation',
        'next_of_kin_name',
        'next_of_kin_relationship',
        'next_of_kin_phone',
        'blood_group',
        'genotype',
        'allergies',
        'medical_conditions',
        'current_medications',
        'has_disability',
        'religion',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'has_disability' => 'boolean',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
