<?php

namespace Modules\Management\ProjectManagement\Project\Validations;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectCommentValidation extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response(['status' => 'validation_error', 'errors' => $validator->errors()->getMessages()], 422)
        );
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|integer',
            'user_id'    => 'sometimes|nullable|integer',
            'name'       => 'sometimes|nullable|string|max:100',
            'email'      => 'sometimes|nullable|email|max:100',
            'phone'      => 'sometimes|nullable|string|max:100',
            'comment'    => 'required|string|min:5|max:5000',
            'creator'    => 'sometimes|nullable|integer',
            'slug'       => 'sometimes|nullable|string|max:50',
            'status'     => 'sometimes|in:active,inactive',
        ];
    }
}
