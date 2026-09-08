<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerJob extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'title',
        'company',
        'job_type',
        'location',
        'salary_range',
        'description',
        'requirements',
        'contact_email',
        'deadline',
        'apply_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requirements' => 'array',
            'deadline' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
