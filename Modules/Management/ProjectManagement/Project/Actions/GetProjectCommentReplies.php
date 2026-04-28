<?php

namespace Modules\Management\ProjectManagement\Project\Actions;

class GetProjectCommentReplies
{
    static $model = \Modules\Management\ProjectManagement\Project\Database\Models\ProjectCommentModel::class;

    public static function execute($comment_id)
    {
        try {
            $pageLimit     = request()->input('limit') ?? 10;
            $orderByColumn = request()->input('sort_by_col') ?? 'id';
            $orderByType   = request()->input('sort_type') ?? 'desc';

            $data = self::$model::with(['user:id,name', 'replies.user:id,name'])
                ->where('parent_id', $comment_id)
                ->orderBy($orderByColumn, $orderByType)
                ->paginate($pageLimit);

            return entityResponse([
                ...$data->toArray(),
                'comment_id'    => (int) $comment_id,
                'total_replies' => self::$model::where('parent_id', $comment_id)->count(),
            ]);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
