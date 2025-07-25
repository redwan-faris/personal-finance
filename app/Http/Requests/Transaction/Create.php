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
            'wallet_id' => 'required|uuid|exists:wallet,id',
            'transaction_category_id' => 'required|uuid|exists:transaction_category,id',
            'amount' => 'required|integer|min:1',
            'currency' => 'required|string|max:3',
            'status' => 'required|string|max:50',
            'type' => ['required', new Enum(TransactionTypeEnum::class)],
            'description' => 'nullable|string|max:1000',
            'direction' => 'required|string|in:in,out',
        ];
    }
}
