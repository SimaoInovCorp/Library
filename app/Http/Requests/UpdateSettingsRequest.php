<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'free_shipping_threshold' => ['required', 'numeric', 'min:0'],
            'max_cart_quantity_per_book' => ['required', 'integer', 'min:1', 'max:100'],
            'abandoned_cart_hours' => ['required', 'integer', 'min:1', 'max:168'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'tax_rate' => 'Tax Rate',
            'shipping_cost' => 'Shipping Cost',
            'free_shipping_threshold' => 'Free Shipping Threshold',
            'max_cart_quantity_per_book' => 'Maximum Cart Quantity Per Book',
            'abandoned_cart_hours' => 'Abandoned Cart Hours',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tax_rate.required' => 'Tax rate is required.',
            'tax_rate.min' => 'Tax rate cannot be negative.',
            'tax_rate.max' => 'Tax rate cannot exceed 100%.',
            'abandoned_cart_hours.max' => 'Abandoned cart hours cannot exceed 7 days (168 hours).',
        ];
    }
}
