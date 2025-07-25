<?php

namespace App\Http\Requests\Exam;

use App\Enums\ExamTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;  // You may check for authorization here.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255|unique:exams,name' . ($this->route('exam') ? ',' . $this->route('exam')->id : ''),
            'description' => 'nullable|string',
            'max_grade' => 'nullable|integer',
            'passing_score' => 'nullable|integer|lte:max_grade',
            'questions_count' => 'nullable|integer|min:1',
            'category' => 'nullable|in:' . implode(',', array_column(ExamTypeEnum::cases(), 'value')),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ];
    }
}
