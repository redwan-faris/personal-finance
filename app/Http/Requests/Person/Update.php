<?php

namespace App\Http\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Update extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('people', 'phone')->ignore($this->person)
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('people', 'email')->ignore($this->person)
            ],
            'balance' => 'integer',
            'note' => 'nullable|string|max:1000',
        ];
    }
}
