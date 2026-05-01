<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Validations;

use Illuminate\Foundation\Http\FormRequest;

class SubmitDigitalProductOrderValidation extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'digital_product_id' => 'required|exists:digital_products,id',
            'first_name' => 'required|string|max:150',
            'last_name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'trx_number' => 'required|string|max:150|unique:product_orders,trx_number',
            'payment_details' => 'nullable|json',
            'payment_status' => 'nullable|in:pending,due,compelete',
        ];
    }

    public function messages()
    {
        return [
            'digital_product_id.required' => 'Product ID is required',
            'digital_product_id.exists' => 'The selected product does not exist',
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be valid',
            'phone.required' => 'Phone number is required',
            'trx_number.required' => 'Transaction ID is required',
            'trx_number.unique' => 'This transaction ID already exists',
        ];
    }
}
