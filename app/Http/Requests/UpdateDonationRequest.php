<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDonationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You can modify this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'campaign_id' => 'required|exists:campaigns,id',
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'is_anonymous' => 'boolean',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,completed,failed',
            'payment_method' => 'required|string|in:cash,credit_card,bank_transfer,other',
            'transaction_id' => 'nullable|string|unique:donations,transaction_id,' . $this->donation->id,
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'campaign_id.required' => 'Please select a campaign',
            'campaign_id.exists' => 'The selected campaign does not exist',
            'donor_name.required' => 'Please enter the donor\'s name',
            'donor_email.required' => 'Please enter the donor\'s email',
            'donor_email.email' => 'Please enter a valid email address',
            'amount.required' => 'Please enter the donation amount',
            'amount.numeric' => 'The amount must be a number',
            'amount.min' => 'The amount must be greater than 0',
            'status.required' => 'Please select a status',
            'status.in' => 'Please select a valid status',
            'payment_method.required' => 'Please select a payment method',
            'payment_method.in' => 'Please select a valid payment method',
            'transaction_id.unique' => 'This transaction ID has already been used',
        ];
    }
} 