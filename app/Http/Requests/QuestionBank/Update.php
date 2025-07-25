<?php

namespace App\Http\Requests\QuestionBank;

use App\Enums\QuestionTypesEnum;
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
            'exam_id' => 'nullable|uuid|exists:exams,id',
            'question_text' => 'nullable|string',
            'choices_count' => 'nullable|integer|min:1',
            'correct_choices_count' => 'nullable|integer|min:1',
            'question_type' =>  'nullable|in:' . implode(',', array_column(QuestionTypesEnum::cases(), 'value')),
            'choices' => 'nullable|array',
            'choices.*.choice_text' => 'nullable|string',
            'choices.*.is_correct_choice' => 'nullable|boolean',
            'category_id' => 'nullable|uuid|exists:question_categories,id'

        ];
    }
}
