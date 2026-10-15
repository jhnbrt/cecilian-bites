<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfirmPaymentRequest extends FormRequest
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
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $method = $this->enum('payment_method', PaymentMethod::class);

        return [
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
            'discount' => ['nullable', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999.99'],
            'cash_received' => [
                Rule::requiredIf($method === PaymentMethod::Cash),
                'nullable', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999.99',
            ],
            'payment_reference' => [
                Rule::requiredIf($method?->isDigital() === true),
                'nullable', 'string', 'max:100',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'payment_reference.required' => 'Enter the verified transaction reference.',
            'cash_received.required' => 'Enter the cash received.',
        ];
    }
}
