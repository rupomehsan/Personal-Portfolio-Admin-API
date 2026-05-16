<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

class DeleteComment
{
    static $model = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentModel::class;

    public static function execute($id)
    {
        try {
            $comment = self::$model::find($id);
            if (!$comment) {
                return messageResponse('Comment not found.', [], 404, 'error');
            }

            self::$model::where('parent_id', $comment->id)->delete();
            $comment->delete();

            return messageResponse('Comment deleted successfully.', [], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
