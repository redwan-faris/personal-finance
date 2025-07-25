<?php
namespace App\Http\Requests\QuestionBankChoice;

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
            'bank_question_id' => 'nullable|uuid|exists:question_bank   ,id',
            'choice_text' => 'nullable|string',
            'is_correct_choice' => 'nullable|boolean',
        ];
    }
}
