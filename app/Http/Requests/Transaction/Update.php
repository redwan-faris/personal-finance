<?php

namespace App\Http\Requests\Transaction;

use App\Enums\TransactionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
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
            'wallet_id' => 'nullable|uuid|exists:wallet,id',
            'transaction_category_id' => 'uuid|exists:transaction_category,id',
            'person_id' => 'nullable|uuid|exists:people,id',
            'amount' => 'integer|min:1',
            'currency' => 'string|max:3',
            'status' => 'string|max:50',
            'type' => [new Enum(TransactionTypeEnum::class)],
            'description' => 'nullable|string|max:1000',
            'direction' => 'string|in:in,out',
        ];
    }
}
