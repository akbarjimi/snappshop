<?php

namespace App\Http\Requests;

use App\Rules\BankCardValidationRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TransferFormRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'origin' => ['required','integer','numeric','digis:16', new BankCardValidationRule()],
            'destination' => ['required','integer','numeric','digis:16', new BankCardValidationRule()],
            'amount' => ['required','integer','numeric','digits_between:5,9','min:10000','max:500000000',],
        ];
    }
}
