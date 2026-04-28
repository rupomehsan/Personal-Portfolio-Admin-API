<?php

namespace Modules\Management\ProjectManagement\Project\Actions;

use Modules\Management\ProjectManagement\Project\Database\Models\ProjectCommentModel;

class DestroyProjectComment
{
    public static function execute($id)
    {
        try {
            $comment = ProjectCommentModel::find($id);

            if (!$comment) {
                return messageResponse('Comment not found...', [], 404, 'error');
            }

            // Remove relevant reply comments if it's a parent comment
            if (!$comment->parent_id) {
                ProjectCommentModel::where('parent_id', $comment->id)->delete();
            }

            // Delete the comment itself
            $comment->delete();

            return messageResponse('Comment has been deleted successfully', [], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
