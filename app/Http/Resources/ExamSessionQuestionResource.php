<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamSessionQuestionResource extends JsonResource
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
            "bank_question_id" => $this->bank_question_id,
            "exam_session_id" => $this->exam_session_id,
            "question" => $this->questionBank,
            'choices' => ExamSessionQuestionChoiceResource::collection($this->choices),
            "weight" => $this->weight,
            'sequence' => $this->sequence,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
