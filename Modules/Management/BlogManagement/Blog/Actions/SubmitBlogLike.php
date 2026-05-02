<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

use Modules\Management\BlogManagement\Blog\Database\Models\BlogLikeModel;

class SubmitBlogLike
{
    public static function execute($blog_id)
    {
        try {
            $ipAddress = request()->ip();

            $recentLike = BlogLikeModel::where('blog_id', $blog_id)
                ->where('ip', $ipAddress)
                ->where('created_at', '>=', now()->subHours(12))
                ->first();

            if ($recentLike) {
                return messageResponse('You have already liked this blog. Please wait 12 hours before liking again.', [], 429, 'error');
            }

            BlogLikeModel::create([
                'blog_id' => $blog_id,
                'ip'      => $ipAddress,
            ]);

            return messageResponse('Like submitted successfully', [], 201, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
