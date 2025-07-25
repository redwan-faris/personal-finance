<?php
namespace App\Http\Requests\QuestionBankChoice;

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
            'bank_question_id' => 'required|uuid|exists:question_bank,id',
            'choice_text' => 'required|string',
            'is_correct_choice' => 'required|boolean',
        ];
    }
}
