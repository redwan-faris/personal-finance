<?php

namespace App\Http\Requests\ExamSessionQuestion;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bank_question_id' => 'nullable|uuid|exists:question_bank,id',
            'exam_session_id' => 'nullable|uuid|exists:exam_sessions,id',
            'weight' => 'nullable|integer',
            'attempt_id' => 'nullable|uuid|exists:exam_session_attempts,id',
            'choices' => 'required|array',
            'choices.*' => 'required|uuid|exists:question_bank_choices,id',
        ];
    }
}
