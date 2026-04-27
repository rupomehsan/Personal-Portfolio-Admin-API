<?php

namespace Modules\Management\BlogManagement\Blog\Validations;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentReplyValidation extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function validateError($data)
    {
        $errorPayload = $data->getMessages();
        return response(['status' => 'validation_error', 'errors' => $errorPayload], 422);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validateError($validator->errors()));
    }

    public function rules(): array
    {
        return [
            'blog_comment_id' => 'required | integer',
            'user_id' => 'sometimes | nullable | integer',
            'name'    => 'sometimes | nullable | string | max:100',
            'comment' => 'required | json | min:5',
            'creator' => 'sometimes | nullable | integer',
            'slug'    => 'sometimes | nullable | string | max:50',
            'status'  => 'sometimes | in:active,inactive',
        ];
    }
}
