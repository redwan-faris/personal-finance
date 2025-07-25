<?php

namespace App\Http\Requests\ExamSessionAttemptChoice;

use Illuminate\Foundation\Http\FormRequest;

class Create extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => 'nullable|string',
            'is_marked' => 'nullable|boolean',
            'exam_session_attempt_id' =>[
                'required',
                'exists:exam_session_attempts,id'
                , function ($attribute, $value, $fail) {
                    $attempt = \App\Models\ExamSessionAttempt::find($value);
                    if ( $attempt && $attempt->status != 'started') {
                        $fail('The exam session attempt must be started.');
                    }
                }
            ],
            'exam_session_question_id' => 'required|exists:exam_session_questions,id',
           'choices' => 'required|array',
            'choices.*' => 'required|exists:exam_session_question_choices,id',
        ];
    }
}
