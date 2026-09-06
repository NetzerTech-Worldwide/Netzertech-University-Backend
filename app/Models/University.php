<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'subdomain',
        'custom_domain',
        'logo_url',
        'primary_color',
        'secondary_color',
        'contact_email',
        'contact_phone',
        'address',
        'paystack_subaccount_code',
        'remita_merchant_id',
        'platform_fee_amount',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'platform_fee_amount' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function faculties(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function programmes(): HasMany
    {
        return $this->hasMany(Programme::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(AdmissionsApplication::class);
    }
}
