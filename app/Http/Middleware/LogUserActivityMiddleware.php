<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Events\UserActivityEvent;

class LogUserActivityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log for authenticated users
        if (auth()->check()) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    private function logActivity($request, $response)
    {
        // Skip logging for certain routes (like asset files, health checks, etc.)
        $skipRoutes = [
            'api/health',
            'storage/',
            'favicon.ico',
            '.css',
            '.js',
            '.png',
            '.jpg',
            '.jpeg',
            '.gif',
            '.svg',
        ];

        $path = $request->getPathInfo();
        foreach ($skipRoutes as $skipRoute) {
            if (str_contains($path, $skipRoute)) {
                return;
            }
        }

        // Determine activity type based on HTTP method and route
        $activityType = $this->getActivityType($request);
        $statusCode = $response->getStatusCode();
        $status = $statusCode >= 200 && $statusCode < 300 ? 'success' : 'error';

        event(new UserActivityEvent(
            auth()->id(),
            [
                'title' => $activityType['title'],
                'status' => $status,
                'status_code' => $statusCode,
                'message' => $activityType['message'],
                'action_type' => $activityType['type'],
            ],
            $request,
            [
                'route_name' => $request->route()?->getName(),
                'response_size' => strlen($response->getContent()),
            ]
        ));
    }

    private function getActivityType($request)
    {
        $method = $request->getMethod();
        $path = $request->getPathInfo();
        $routeName = $request->route()?->getName();

        // Map common routes to meaningful activity descriptions
        $activityMap = [
            'GET' => [
                'title' => 'Page View',
                'message' => "Visited {$path}",
                'type' => 'page_view',
            ],
            'POST' => [
                'title' => 'Data Creation',
                'message' => "Created data via {$path}",
                'type' => 'create',
            ],
            'PUT' => [
                'title' => 'Data Update',
                'message' => "Updated data via {$path}",
                'type' => 'update',
            ],
            'PATCH' => [
                'title' => 'Data Update',
                'message' => "Updated data via {$path}",
                'type' => 'update',
            ],
            'DELETE' => [
                'title' => 'Data Deletion',
                'message' => "Deleted data via {$path}",
                'type' => 'delete',
            ],
        ];

        return $activityMap[$method] ?? [
            'title' => 'General Activity',
            'message' => "Performed {$method} request to {$path}",
            'type' => 'general',
        ];
    }
}
