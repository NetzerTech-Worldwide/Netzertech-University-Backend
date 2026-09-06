<?php

namespace App\Traits;

use App\Models\University;
use App\Scopes\TenantScope;
use App\Services\Tenant\TenantManager;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    /**
     * Boot the tenant trait for an Eloquent model.
     */
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            $tenantManager = app(TenantManager::class);

            if ($tenantManager->hasTenant() && empty($model->university_id)) {
                $model->university_id = $tenantManager->tenantId();
            }
        });
    }

    /**
     * The university to which this entity belongs.
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class, 'university_id');
    }
}
