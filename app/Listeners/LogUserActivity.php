<?php

namespace App\Listeners;

use App\Events\UserActivityEvent;
use Modules\Management\UserManagement\User\Database\Models\UserLogModel;
use Illuminate\Support\Facades\Log;

class LogUserActivity
{
    public function handle(UserActivityEvent $event)
    {
        try {
            UserLogModel::create([
                'user_id' => $event->userId,
                'last_seen' => now(),
                'log_details' => json_encode([
                    'title' => $event->activity['title'] ?? 'User Activity',
                    'status' => $event->activity['status'] ?? 'success',
                    'status_code' => $event->activity['status_code'] ?? 200,
                    'message' => $event->activity['message'] ?? 'User performed an action',
                    'action_type' => $event->activity['action_type'] ?? 'general',
                    'ip' => $event->request->ip(),
                    'time' => now()->toDateTimeString(),
                    'referer' => $event->request->header('referer'),
                    'request_url' => $event->request->fullUrl(),
                    'method' => $event->request->method(),
                    'user_agent' => $event->request->userAgent(),
                    'additional_data' => $event->additionalData,
                ]),
            ]);
        } catch (\Exception $e) {
            // Log to Laravel's default log if user logging fails
            Log::error('Failed to log user activity: ' . $e->getMessage());
        }
    }
}
