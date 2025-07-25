<?php

namespace App\Http\Requests\Exam;

use App\Enums\ExamTypeEnum;
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
            'name' => 'required|string|max:255|unique:exams,name',
            'description' => 'nullable|string',
            'questions_count' => 'required|integer|min:1',
            'max_grade' => 'required|integer',
            'passing_score' => 'required|integer|lte:max_grade',
            'category' => 'required|in:' . implode(',', array_column(ExamTypeEnum::cases(), 'value')),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
