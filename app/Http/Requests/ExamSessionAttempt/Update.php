<?php

namespace App\Http\Requests\ExamSessionAttempt;

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
            'exam_session_id' => 'nullable|exists:exam_sessions,id',
            'student_id' => 'nullable|exists:students,id',
            'is_approved' => 'nullable|boolean',
            'total_grade' => 'nullable|integer',
            'status' => 'nullable'
        ];
    }
}
