<?php

namespace Modules\Management\ProjectManagement\Project\Actions;

class SubmitProjectComment
{
    static $model = \Modules\Management\ProjectManagement\Project\Database\Models\ProjectCommentModel::class;

    public static function execute($request)
    {
        try {
            $data = self::$model::create($request->validated());
            if ($data) {
                return messageResponse('Comment submitted successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
