<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

class SubmitComment
{
    static $model = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentModel::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();
            
            // Generate slug
            if (!isset($requestData['slug'])) {
                $requestData['slug'] = \Illuminate\Support\Str::slug($requestData['comment'] . '-' . time());
            }

            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Comment submitted successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
