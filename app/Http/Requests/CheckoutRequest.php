<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shipping_address' => 'required|string|max:500',
            'billing_address' => 'required|string|max:500',
            'phone' => 'required|string|regex:/^[0-9+\-\s()]+$/|max:20',
            'email' => 'required|email|max:255',
            'payment_method' => 'required|string|in:credit_card,debit_card,paypal,bank_transfer',
            'card_number' => 'required_if:payment_method,credit_card,debit_card|nullable|string|regex:/^[0-9\s\-]+$/|min:13|max:19',
            'card_holder_name' => 'required_if:payment_method,credit_card,debit_card|nullable|string|max:255',
            'card_expiry' => 'required_if:payment_method,credit_card,debit_card|nullable|string|regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
            'card_cvv' => 'required_if:payment_method,credit_card,debit_card|nullable|string|regex:/^[0-9]{3,4}$/',
            'special_instructions' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'shipping_address.required' => 'Shipping address is required.',
            'billing_address.required' => 'Billing address is required.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid phone number.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Please select a valid payment method.',
            'card_number.required_if' => 'Card number is required for card payments.',
            'card_number.regex' => 'Please enter a valid card number.',
            'card_holder_name.required_if' => 'Card holder name is required for card payments.',
            'card_expiry.required_if' => 'Card expiry date is required for card payments.',
            'card_expiry.regex' => 'Please enter expiry date in MM/YY format.',
            'card_cvv.required_if' => 'CVV is required for card payments.',
            'card_cvv.regex' => 'CVV must be 3 or 4 digits.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean card number (remove spaces and dashes for validation)
        if ($this->has('card_number')) {
            $this->merge([
                'card_number' => preg_replace('/[\s\-]/', '', $this->card_number),
            ]);
        }
    }
}