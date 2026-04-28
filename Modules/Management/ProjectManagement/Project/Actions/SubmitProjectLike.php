<?php

namespace Modules\Management\ProjectManagement\Project\Actions;

use Modules\Management\ProjectManagement\Project\Database\Models\ProjectLikeModel;

class SubmitProjectLike
{
    public static function execute($project_id)
    {
        try {
            $ipAddress = request()->ip();
            $sessionId = request()->hasSession() ? request()->session()->getId() : request()->header('User-Agent');

            $existingLike = ProjectLikeModel::where('project_id', $project_id)
                ->where(function($query) use ($ipAddress, $sessionId) {
                    $query->where('ip', $ipAddress)
                          ->orWhere('session_id', $sessionId);
                })
                ->first();

            if ($existingLike) {
                // If it exists, they are toggling to unlike
                $existingLike->delete();
                return messageResponse('Like removed successfully', [], 200, 'success');
            } else {
                // If it does not exist, insert like
                ProjectLikeModel::create([
                    'project_id' => $project_id,
                    'ip' => $ipAddress,
                    'session_id' => $sessionId,
                ]);
                return messageResponse('Like submitted successfully', [], 201, 'success');
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}