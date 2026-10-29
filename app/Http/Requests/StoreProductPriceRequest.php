<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductPriceRequest extends FormRequest
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
            'currency_id' => [
                'required', 'integer', 'exists:currencies,id',
                Rule::unique('product_prices')->where('product_id', $this->route('product')->id),
            ],
            // Opcional: si no se envía, se calcula con la tasa de cambio.
            'price' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
        ];

    }

    public function messages(): array
    {
        return [
            'currency_id.unique' => 'El producto ya tiene un precio registrado en esa divisa.',
        ];
    }

}
