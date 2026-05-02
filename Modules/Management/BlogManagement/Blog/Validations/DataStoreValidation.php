<?php

namespace Modules\Management\BlogManagement\Blog\Validations;

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
            'blog_category_id' => 'required | sometimes',
            'title' => 'required | sometimes',
            'description' => 'required | sometimes',
            'content' => ' sometimes',
            'reading_time' => ' sometimes',
            'tags' => ' sometimes',
            'publish_date' => ' sometimes',
            'writer' => ' sometimes',
            'thumbnail_image' => ' sometimes',
            'images'            => 'sometimes|nullable|array',
            'images.*'          => 'sometimes|file|mimes:jpg,jpeg,png,webp,gif,avif,svg',
            'images_kept'       => 'sometimes|nullable|array',
            'images_kept.*'     => 'sometimes|string',
            'images_present'    => 'sometimes|nullable',
            'thumbnail_image_clear' => 'sometimes|nullable',
            'blog_type' => ' sometimes',
            'url' => ' sometimes',
            'show_top' => ' sometimes',
            'contributors' => ' sometimes',
            'video_link' => ' sometimes',
            'is_featured' => ' sometimes',
            'is_published' => ' sometimes',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ];
    }
}