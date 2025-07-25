<?php

namespace App\Http\Requests\Student;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
            'full_name' => 'nullable|string',
            'gender' => ['nullable', new Enum(GenderEnum::class)],

            "phone_number" => [
                "phone:country_code",
                "unique:users,phone_number"
            ],
            "country_code" => "required_with:phone_number",
            "email" => [
                Rule::requiredIf(is_null($this->phone_number) ),
                "email", "unique:users,email"
            ],
            "birth_date" => "nullable|date",
            "is_blocked" => "boolean",
            "notes" => "nullable|string",
            "address" => "nullable|string|max:255",
            "city" => "nullable|string|max:50",

            "country" => "nullable|string|max:100",
            "postal_code" => "nullable|string|max:20",
            "working_field" => "nullable|string|max:100",
            "university" => "nullable|string|max:100",
            "years_of_experience" => "nullable|string|max:50",
         ];

    }
}
