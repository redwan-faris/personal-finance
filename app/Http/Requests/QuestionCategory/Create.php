<?php

namespace App\Http\Requests\QuestionCategory;

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
            'name'=>'required|string|unique:question_categories,name',
            'exam_id' => 'required|uuid|exists:exams,id',

        ];
    }
}
