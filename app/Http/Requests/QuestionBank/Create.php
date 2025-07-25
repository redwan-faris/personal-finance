<?php

namespace App\Http\Requests\QuestionBank;

use App\Enums\QuestionTypesEnum;
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
            'exam_id' => 'required|uuid|exists:exams,id',
            'question_text' => 'required|string',
            'choices_count' => 'required|integer|min:1',
            'correct_choices_count' => 'required|integer|min:1',
            'question_type' =>  'required|in:' . implode(',', array_column(QuestionTypesEnum::cases(), 'value')),
            'choices' => 'required|array',
            'choices.*.choice_text' => 'required|string',
            'choices.*.is_correct_choice' => 'required|boolean',
            'category_id' => 'required|uuid|exists:question_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
