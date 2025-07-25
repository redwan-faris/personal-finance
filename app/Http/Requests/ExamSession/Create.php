<?php

namespace App\Http\Requests\ExamSession;

use Illuminate\Foundation\Http\FormRequest;

class Create extends FormRequest
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
            'exam_id' => 'required|uuid|exists:exams,id',
            'session_name' => 'nullable|string',
            'session_date' => 'required|date',
            'max_attempts_count' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'duration' => 'required|integer|min:1',
        ];
    }
}
