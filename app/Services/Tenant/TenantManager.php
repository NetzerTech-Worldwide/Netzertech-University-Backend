<?php

namespace App\Services\Tenant;

use App\Models\University;

class TenantManager
{
    protected ?University $tenant = null;

    /**
     * Set the active tenant university.
     */
    public function setTenant(?University $tenant): self
    {
        $this->tenant = $tenant;
        return $this;
    }

    /**
     * Get the active tenant university.
     */
    public function getTenant(): ?University
    {
        return $this->tenant;
    }

    /**
     * Check if a tenant is active.
     */
    public function hasTenant(): bool
    {
        return $this->tenant !== null;
    }

    /**
     * Get the current active tenant ID.
     */
    public function tenantId(): ?int
    {
        return $this->tenant?->id;
    }
}
