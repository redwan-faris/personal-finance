<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
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
            "name" => $this->name,
            "description" => $this->description,
            "max_grade" => $this->max_grade,
            "passing_score" => $this->passing_score,
            "questions_count" => $this->questions_count,
            "image_path" => $this->image_path,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
