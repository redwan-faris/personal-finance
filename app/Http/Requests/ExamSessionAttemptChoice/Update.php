<?php
namespace App\Http\Requests\ExamSessionAttemptChoice;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exam_session_attempt_id' => 'nullable|exists:exam_session_attempts,id',
            'exam_session_question_id' => 'nullable|exists:exam_session_questions,id',
            'exam_session_question_choice_id' => 'nullable|exists:exam_session_question_choices,id',
        ];
    }
}
