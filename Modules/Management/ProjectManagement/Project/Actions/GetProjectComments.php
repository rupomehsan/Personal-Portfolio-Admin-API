<?php

namespace Modules\Management\ProjectManagement\Project\Actions;

class GetProjectComments
{
    static $model = \Modules\Management\ProjectManagement\Project\Database\Models\ProjectCommentModel::class;

    public static function execute($project_id)
    {
        try {
            $pageLimit     = request()->input('limit') ?? 10;
            $orderByColumn = request()->input('sort_by_col') ?? 'id';
            $orderByType   = request()->input('sort_type') ?? 'desc';
            $status        = request()->input('status') ?? 'active';

            $data = self::$model::query()
                ->with(['project:id,name', 'user:id,name', 'replies.user:id,name'])
                ->where('project_id', $project_id)
                ->whereNull('parent_id')
                ->where('status', $status)
                ->orderBy($orderByColumn, $orderByType)
                ->paginate($pageLimit);

            return entityResponse([
                ...$data->toArray(),
                'total_comments'    => self::$model::where('project_id', $project_id)->whereNull('parent_id')->count(),
                'approved_comments' => self::$model::where('project_id', $project_id)->whereNull('parent_id')->where('status', 'active')->count(),
            ]);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
