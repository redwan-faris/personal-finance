<?php

namespace App\Http\Requests\Transaction;

use App\Enums\TransactionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'wallet_id' => 'nullable|uuid|exists:wallet,id',
            'transaction_category_id' => 'required|uuid|exists:transaction_category,id',
            'person_id' => 'nullable|uuid|exists:people,id',
            'amount' => 'required|integer|min:1',
            'status' => 'required|string|max:50',
            'type' => ['required', new Enum(TransactionTypeEnum::class)],
            'description' => 'nullable|string|max:1000',
            'direction' => 'required|string|in:in,out',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->wallet_id && !$this->person_id) {
                $validator->errors()->add('wallet_id', 'Either wallet_id or person_id is required.');
            }
        });
    }
}
