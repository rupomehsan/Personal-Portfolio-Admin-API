<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions;

use Modules\Management\ProductManagement\DigitalProduct\Database\Models\DigitalProductCommentModel;

class DestroyDigitalProductComment
{
    public static function execute($id)
    {
        try {
            $comment = DigitalProductCommentModel::find($id);

            if (!$comment) {
                return messageResponse('Comment not found...', [], 404, 'error');
            }

            if (!$comment->parent_id) {
                DigitalProductCommentModel::where('parent_id', $comment->id)->delete();
            }

            $comment->delete();

            return messageResponse('Comment has been deleted successfully', [], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
