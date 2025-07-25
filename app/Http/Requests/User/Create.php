<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'full_name' => 'required|string',
            "password" => [
                "required",
                "min:8", "nullable",
                "confirmed"
            ],
            "phone_number" => [
                Rule::requiredIf(is_null($this->email)),
                "phone:phone_country",
                "unique:users,phone_number"
            ],
            "phone_country" => "required_with:phone_number",
            "email" => [
                Rule::requiredIf(is_null($this->phone_number) ),
                "email", "unique:users,email"
            ],
        ];
    }
}

