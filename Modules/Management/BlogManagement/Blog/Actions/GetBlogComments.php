<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

class GetBlogComments
{
    static $model = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentModel::class;

    public static function execute($blog_id)
    {
        try {
            $pageLimit = request()->input('limit') ?? 10;
            $orderByColumn = request()->input('sort_by_col') ?? 'created_at';
            $orderByType = request()->input('sort_type') ?? 'desc';
            $status = request()->input('status') ?? 'active';

            $data = self::$model::query()
                ->with(['blog:id,title', 'user:id,name'])
                ->where('blog_id', $blog_id)
                ->whereNull('parent_id')
                ->where('status', $status)
                ->orderBy($orderByColumn, $orderByType)
                ->paginate($pageLimit);

            return entityResponse([
                ...$data->toArray(),
                "total_comments" => self::$model::where('blog_id', $blog_id)->whereNull('parent_id')->count(),
                "approved_comments" => self::$model::where('blog_id', $blog_id)->whereNull('parent_id')->where('status', 'active')->count(),
            ]);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
