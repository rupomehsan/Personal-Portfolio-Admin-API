<?php

namespace Modules\Management\ProjectManagement\Project\Actions;

use Modules\Management\ProjectManagement\Project\Database\Models\ProjectLikeModel;

class SubmitProjectLike
{
    public static function execute($project_id)
    {
        try {
            $ipAddress = request()->ip();

            $recentLike = ProjectLikeModel::where('project_id', $project_id)
                ->where('ip', $ipAddress)
                ->where('created_at', '>=', now()->subHours(12))
                ->first();

            if ($recentLike) {
                return messageResponse('You have already liked this project. Please wait 12 hours before liking again.', [], 429, 'error');
            }

            ProjectLikeModel::create([
                'project_id' => $project_id,
                'ip'         => $ipAddress,
            ]);

            return messageResponse('Like submitted successfully', [], 201, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}