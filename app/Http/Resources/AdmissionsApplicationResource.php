<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdmissionsApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_no' => $this->application_no,
            'status' => $this->status,
            'app_fee_paid' => $this->app_fee_paid,
            'screening_score' => $this->screening_score ? (float) $this->screening_score : null,
            'offer_accepted' => $this->offer_accepted,
            'acceptance_fee_paid' => $this->acceptance_fee_paid,
            'profile_complete' => $this->profile_complete,
            'documents_verified' => $this->documents_verified,
            'matric_number_issued' => $this->matric_number_issued,
            'first_choice_programme' => $this->firstChoiceProgramme ? [
                'id' => $this->firstChoiceProgramme->id,
                'name' => $this->firstChoiceProgramme->name,
                'department' => $this->firstChoiceProgramme->department?->name,
                'faculty' => $this->firstChoiceProgramme->department?->faculty?->name,
            ] : null,
            'second_choice_programme' => $this->secondChoiceProgramme ? [
                'id' => $this->secondChoiceProgramme->id,
                'name' => $this->secondChoiceProgramme->name,
                'department' => $this->secondChoiceProgramme->department?->name,
            ] : null,
            'jamb' => $this->jambRecord,
            'olevel' => $this->olevelResults,
            'post_utme' => $this->postUtmeSlot,
            'uploaded_documents' => $this->uploaded_documents,
            'steps_completed' => $this->steps_completed,
            'submitted_at' => $this->submitted_at?->toIso8601String(),
        ];
    }
}
