<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'staff_no' => $this->staff_no,
            'title' => $this->title,
            'designation' => $this->designation,
            'rank' => $this->rank,
            'initials' => $this->initials,
            'color' => $this->color,
            'department' => $this->department ? [
                'id' => $this->department->id,
                'name' => $this->department->name,
                'code' => $this->department->code,
            ] : null,
            'faculty' => $this->faculty ? [
                'id' => $this->faculty->id,
                'name' => $this->faculty->name,
                'code' => $this->faculty->code,
            ] : null,
        ];
    }
}
