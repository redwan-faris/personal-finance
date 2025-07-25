<?php

namespace App\Http\Requests\Person;

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
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:people,phone',
            'email' => 'nullable|email|unique:people,email',
            'balance' => 'integer',
            'note' => 'nullable|string|max:1000',
        ];
    }
}
