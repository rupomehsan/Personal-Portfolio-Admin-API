<?php

namespace Modules\Management\UserManagement\User\Validations;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DataUpdateValidation extends FormRequest
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
            'role_id' => 'required | sometimes',
            'user_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'image' => 'nullable | sometimes',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->route('slug'), 'slug'),
            ],
            'password' => 'nullable|string|min:6',
            'state' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'post' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:255',
            'social_media' => 'nullable|array',
            'social_media.*.media_name' => 'nullable|string|max:255',
            'social_media.*.media_link' => 'nullable|string|max:255',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ];
    }
}
