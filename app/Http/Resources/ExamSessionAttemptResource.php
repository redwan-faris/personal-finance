<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamSessionAttemptResource extends JsonResource
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
            "exam_session_id" => $this->exam_session_id,
            "image_path" => $this->image_path,
            "signature_path" => $this->signature_path,
            "custom_id" => $this->custom_id,
            "student_id" => $this->student_id,
            "is_approved" => $this->is_approved,
            "total_grade" => $this->total_grade,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
