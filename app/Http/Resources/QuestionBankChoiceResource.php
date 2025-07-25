<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankChoiceResource extends JsonResource
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
            "choice_text" => $this->choice_text,
            "is_correct_choice" => $this->is_correct_choice,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
