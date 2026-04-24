<?php

namespace Modules\Management\UserManagement\User\Validations;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserProfileUpdateValidation extends FormRequest
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

            'email' => 'required | sometimes',
            Rule::unique('users', 'email')->ignore(auth()->user()->id),
            'user_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'image' => 'nullable | sometimes',

            'password' => 'nullable|string|min:6',
            'state' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'post' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'phone_numbers' => 'sometimes',
            'social_media' => 'sometimes',
        ];
    }
}
