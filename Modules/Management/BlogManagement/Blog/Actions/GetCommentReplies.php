<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

class GetCommentReplies
{
    static $replyModel    = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentReplyModel::class;
    static $junctionModel = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentBlogCommentReplyModel::class;

    public static function execute($comment_id)
    {
        try {
            $pageLimit       = request()->input('limit') ?? 10;
            $orderByColumn   = request()->input('sort_by_col') ?? 'id';
            $orderByType     = request()->input('sort_type') ?? 'desc';

            $replyIds = self::$junctionModel::where('blog_comment_id', $comment_id)
                ->pluck('blog_comment_reply_id');

            $data = self::$replyModel::with(['user:id,name'])
                ->whereIn('id', $replyIds)
                ->orderBy($orderByColumn, $orderByType)
                ->paginate($pageLimit);

            return entityResponse([
                ...$data->toArray(),
                'comment_id'    => (int) $comment_id,
                'total_replies' => $replyIds->count(),
            ]);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
