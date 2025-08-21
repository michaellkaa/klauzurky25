<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class FacultyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'university' => $this->university,
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'website' => $this->website,
            'email' => $this->email,
            'phone' => $this->phone,
            'application_link' => $this->application_link,
            'admission_notes' => $this->admission_notes,
            'open_day_dates' => $this->open_day_dates,
            'open_day_url' => $this->open_day_url,
            'exam_dates' => $this->exam_dates,
            'application_fee' => $this->application_fee,
            'application_deadlines' => $this->application_deadlines,
            'bc_programs' => $this->bc_programs,
            'mgr_programs' => $this->mgr_programs,
            'dr_programs' => $this->dr_programs,
            'logo_url' => $this->logo_url,
            'banner_url' => $this->banner_url,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'twitter_url' => $this->twitter_url,
            'fields_of_study' => $this->fields_of_study,
            'is_favorite' => $this->favoritedByUsers()->where('user_id', Auth::id())->exists()
        ];
    }
}
