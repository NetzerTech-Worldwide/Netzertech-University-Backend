<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JambRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'reg_number',
        'score',
        'year',
        'institution_chosen',
        'course_chosen',
        'verified',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'year' => 'integer',
            'verified' => 'boolean',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(AdmissionsApplication::class, 'application_id');
    }
}
