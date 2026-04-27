<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Validations;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DataStoreValidation extends FormRequest
{
    /**
     * Determine if the  is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * validateError to make this request.
     */
    public function validateError($data)
    {
        $errorPayload =  $data->getMessages();
        return response(['status' => 'validation_error', 'errors' => $errorPayload], 422);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validateError($validator->errors()));
        if ($this->wantsJson() || $this->ajax()) {
            throw new HttpResponseException($this->validateError($validator->errors()));
        }
        parent::failedValidation($validator);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required | sometimes',
            'title' => 'required | sometimes',
            'short_description' => 'required | sometimes',
            'description' => 'required | sometimes',
            'regular_price' => 'required | sometimes',
            'sale_price' => 'required | sometimes',
            'demo_url' => 'required | sometimes',
            'file_path' => 'required | sometimes',
            'file_type' => 'required | sometimes',
            'version' => 'required | sometimes',
            'thumbnail_image' => 'required | sometimes',
            'gallery_images' => 'required | sometimes',
            'features' => 'required | sometimes',
            'tags' => 'required | sometimes',
            'is_active' => 'required | sometimes',
            'is_featured' => 'required | sometimes',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ];
    }
}