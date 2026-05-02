<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

use Modules\Management\BlogManagement\Blog\Database\Models\BlogViewModel;
use Modules\Management\BlogManagement\Blog\Database\Models\BlogLikeModel;
use Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentModel;

class GetSingleBlog
{
    static $model = \Modules\Management\BlogManagement\Blog\Database\Models\Model::class;

    public static function execute($slug)
    {
        try {
            $fields = request()->input('fields') ?? ['*'];

            if (!$data = self::$model::query()->select($fields)->where('slug', $slug)->first()) {
                return messageResponse('Data not found...', [], 404, 'error');
            }

            // Track view (deduplicated per IP per hour)
            $ipAddress = request()->ip();

            $viewExists = BlogViewModel::where('blog_id', $data->id)
                ->where('ip', $ipAddress)
                ->where('created_at', '>=', now()->subHours(1))
                ->exists();

            if (!$viewExists) {
                BlogViewModel::create([
                    'blog_id' => $data->id,
                    'ip'      => $ipAddress,
                ]);
            }

            $data->total_views = BlogViewModel::where('blog_id', $data->id)->count();
            $data->total_likes = BlogLikeModel::where('blog_id', $data->id)->count();

            // Include comments with their nested replies and user info
            $data->comments = BlogCommentModel::query()
                ->with(['user:id,name', 'replies.user:id,name', 'replies.replies.user:id,name'])
                ->where('blog_id', $data->id)
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
