<?php

namespace App\Http\Requests\ApproveStudent;

use App\Enums\ExamTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class ApproveStudentRequest extends FormRequest
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
            'attempt_id' => 'required|exists:exam_session_attempts,id',
            'student_id' => 'required|exists:students,id',
        ];
    }
}
