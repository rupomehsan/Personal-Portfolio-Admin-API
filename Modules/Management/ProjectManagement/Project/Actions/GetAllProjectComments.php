<?php

namespace Modules\Management\ProjectManagement\Project\Actions;

class GetAllProjectComments
{
    static $model = \Modules\Management\ProjectManagement\Project\Database\Models\ProjectCommentModel::class;

    public static function execute()
    {
        try {
            $pageLimit     = request()->input('limit') ?? 10;
            $orderByColumn = request()->input('sort_by_col') ?? 'id';
            $orderByType   = request()->input('sort_type') ?? 'desc';
            $status        = request()->input('status') ?? 'active';
            $start_date    = request()->input('start_date');
            $end_date      = request()->input('end_date');

            $data = self::$model::query()
                ->with(['project:id,name', 'user:id,name'])
                ->whereNull('parent_id');

            if (request()->filled('search')) {
                $searchKey = request()->input('search');
                $data->where(function ($q) use ($searchKey) {
                    $q->where('comment', 'like', '%' . $searchKey . '%')
                      ->orWhere('name', 'like', '%' . $searchKey . '%');
                });
            }

            if ($start_date && $end_date) {
                if ($end_date > $start_date) {
                    $data->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                } elseif ($end_date === $start_date) {
                    $data->whereDate('created_at', $start_date);
                }
            }

            if ($status === 'trashed') {
                $data->onlyTrashed();
            } else {
                $data->where('status', $status);
            }

            $result = $data->orderBy($orderByColumn, $orderByType)->paginate($pageLimit);

            return entityResponse([
                ...$result->toArray(),
                'active_data_count'   => 0,
                'inactive_data_count' => 0,
                'trashed_data_count'  => 0,
            ]);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
