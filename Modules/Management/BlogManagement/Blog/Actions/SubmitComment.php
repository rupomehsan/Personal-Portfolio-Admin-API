<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

class SubmitComment
{
    static $model = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentModel::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();
            
            // Auto-populate user_id if authenticated user is posting the comment
            $requestData['user_id'] = $requestData['user_id'] ?? (auth()->check() ? auth()->id() : null);
            
            // Generate slug if not provided
            if (!isset($requestData['slug'])) {
                $requestData['slug'] = \Illuminate\Support\Str::slug($requestData['comment'] . '-' . time());
            }

            // Set default status to active if not provided
            if (!isset($requestData['status'])) {
                $requestData['status'] = 'active';
            }

            if ($data = self::$model::query()->create($requestData)) {
                // Load the user relationship if it exists
                $data->load('user');
                return messageResponse('Comment submitted successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
