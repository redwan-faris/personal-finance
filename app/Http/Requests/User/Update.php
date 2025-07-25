<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
            'full_name' => 'string',
            "password" => [
                "min:8", "nullable",
                "confirmed"
            ],
            "phone_number" => [
                "phone:phone_country",
                "unique:users,phone_number"
            ],
            "phone_country" => "required_with:phone_number",
            "email" => [
                "email", "unique:users,email"
            ],
        ];
    }
}
