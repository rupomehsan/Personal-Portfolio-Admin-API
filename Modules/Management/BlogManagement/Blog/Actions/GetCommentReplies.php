<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

class GetCommentReplies
{
    static $commentModel = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentModel::class;

    public static function execute($comment_id)
    {
        try {
            $pageLimit       = request()->input('limit') ?? 10;
            $orderByColumn   = request()->input('sort_by_col') ?? 'id';
            $orderByType     = request()->input('sort_type') ?? 'desc';

            // Load replies using the unified BlogCommentModel with parent_id
            $data = self::$commentModel::with(['user:id,name', 'replies.user:id,name'])
                ->where('parent_id', $comment_id)
                ->orderBy($orderByColumn, $orderByType)
                ->paginate($pageLimit);

            // Count total replies (including nested ones)
            $totalReplies = self::$commentModel::where('parent_id', $comment_id)->count();

            return entityResponse([
                ...$data->toArray(),
                'comment_id'    => (int) $comment_id,
                'total_replies' => $totalReplies,
            ]);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
