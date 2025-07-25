<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankResource extends JsonResource
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
            'exam_id' => $this->exam_id,
            'question_text' => $this->question_text,
            'choices_count' => $this->choices_count,
            'question_type' => $this->question_type,
            'category' => $this->category,
            'correct_choices_count' => $this->correct_choices_count,
            'choices' => QuestionBankChoiceResource::collection($this->choices),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
