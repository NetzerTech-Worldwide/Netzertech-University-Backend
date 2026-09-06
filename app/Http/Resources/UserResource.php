<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'user_type' => $this->user_type,
            'is_active' => $this->is_active,
            'avatar_url' => $this->avatar_url,
            'roles' => $this->getRoleNames(),
            'permissions' => $this->getAllPermissions()->pluck('name'),
            'university' => $this->university ? [
                'id' => $this->university->id,
                'name' => $this->university->name,
                'code' => $this->university->code,
                'subdomain' => $this->university->subdomain,
                'logo_url' => $this->university->logo_url,
                'primary_color' => $this->university->primary_color,
            ] : null,
            'student' => new StudentResource($this->whenLoaded('student')),
            'staff' => new StaffResource($this->whenLoaded('staff')),
            'application' => new AdmissionsApplicationResource($this->whenLoaded('application')),
        ];
    }
}
