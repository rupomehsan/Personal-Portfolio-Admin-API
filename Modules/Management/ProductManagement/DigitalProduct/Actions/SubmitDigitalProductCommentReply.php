<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions;

class SubmitDigitalProductCommentReply
{
    static $model = \Modules\Management\ProductManagement\DigitalProduct\Database\Models\DigitalProductCommentModel::class;

    public static function execute($request)
    {
        try {
            $data = self::$model::create($request->validated());
            if ($data) {
                return messageResponse('Reply submitted successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
