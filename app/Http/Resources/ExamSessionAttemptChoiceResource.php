<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamSessionAttemptChoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'exam_session_attempt_id' => $this->exam_session_attempt_id,
            'exam_session_question_id' => $this->exam_session_question_id,
            'exam_session_question_choice_id' => $this->exam_session_question_choice_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
