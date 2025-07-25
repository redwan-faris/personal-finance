<?php

namespace App\Http\Requests\QuestionCategory;

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
            'name'=>'nullable|string|unique:question_categories,name' . ($this->route('question_category') ? ',' . $this->route('question_category')->id : ''),
            'exam_id' => 'nullable|uuid|exists:exams,id',

        ];
    }
}
