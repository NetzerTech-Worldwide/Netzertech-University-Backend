<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'matric_number' => $this->matric_number,
            'jamb_reg_no' => $this->jamb_reg_no,
            'level' => $this->level,
            'academic_session' => $this->academic_session,
            'current_semester' => $this->current_semester,
            'entry_mode' => $this->entry_mode,
            'cgpa' => (float) $this->cgpa,
            'standing' => $this->standing,
            'digital_id_token' => $this->digital_id_token,
            'faculty' => $this->faculty ? [
                'id' => $this->faculty->id,
                'name' => $this->faculty->name,
                'code' => $this->faculty->code,
            ] : null,
            'department' => $this->department ? [
                'id' => $this->department->id,
                'name' => $this->department->name,
                'code' => $this->department->code,
            ] : null,
            'programme' => $this->programme ? [
                'id' => $this->programme->id,
                'name' => $this->programme->name,
                'degree' => $this->programme->degree,
            ] : null,
            'profile' => $this->profile,
        ];
    }
}
