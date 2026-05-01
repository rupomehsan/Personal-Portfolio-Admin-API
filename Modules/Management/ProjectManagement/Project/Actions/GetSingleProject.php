<?php

namespace Modules\Management\ProjectManagement\Project\Actions;

use Modules\Management\ProjectManagement\Project\Database\Models\ProjectViewModel;
use Modules\Management\ProjectManagement\Project\Database\Models\ProjectLikeModel;
use Modules\Management\ProjectManagement\Project\Database\Models\ProjectCommentModel;

class GetSingleProject
{
    static $model = \Modules\Management\ProjectManagement\Project\Database\Models\Model::class;

    public static function execute($slug)
    {
        try {
            $with = [];

            $fields = request()->input('fields') ?? ['*'];
            if (!$data = self::$model::query()->with($with)->select($fields)->where('slug', $slug)->first()) {
                return messageResponse('Data not found...', [], 404, 'error');
            }

            // Track View
            $ipAddress = request()->ip();
            
            // Check if this user/session has likely viewed this project recently to prevent spam (optional)
            $viewExists = ProjectViewModel::where('project_id', $data->id)
                ->where('ip', $ipAddress)
                ->where('created_at', '>=', now()->subHours(1))
                ->exists();

            if (!$viewExists) {
                ProjectViewModel::create([
                    'project_id' => $data->id,
                    'ip' => $ipAddress,
                ]);
            }

            $data->total_views = ProjectViewModel::where('project_id', $data->id)->count();
            $data->total_likes = ProjectLikeModel::where('project_id', $data->id)->count();

            $data->comments = ProjectCommentModel::query()
                ->with(['replies'])
                ->where('project_id', $data->id)
                ->whereNull('parent_id')
                ->where('status', 'active')
                ->orderBy('id', 'desc')
                ->get();

            return entityResponse($data);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}