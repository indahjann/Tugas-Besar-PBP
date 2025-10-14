<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Authorize user making the request. Only authenticated users can checkout.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Validation rules for checkout payload.
     */
    public function rules(): array
    {
        return [
            'address_text' => 'required|string|max:1000',
            'phone_number' => 'nullable|string|max:20',
            'shipping_method' => 'nullable|string|in:standard,express',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'address_text.required' => 'Alamat pengiriman harus diisi.',
        ];
    }
}
