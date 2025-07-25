<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "birth_date" => $this->birth_date,
            "notes" => $this->notes,
            "address" => $this->address,
            "city" => $this->city,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at,
            'phone_number' => $this->phone_number,
            'country_code' => $this->country_code,
        ];
    }
}
//
