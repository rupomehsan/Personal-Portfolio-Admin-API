<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

class SubmitCommentReply
{
    static $commentModel = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentModel::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();
            
            // Get the parent comment to extract blog_id
            $parentComment = self::$commentModel::find($requestData['blog_comment_id']);
            if (!$parentComment) {
                return messageResponse('Parent comment not found', [], 404, 'error');
            }

            // Resolve authenticated admin via api guard (works even without auth middleware)
            $authUser = auth('api')->user();

            // Prepare reply data using the unified BlogCommentModel
            $replyData = [
                'blog_id'   => $parentComment->blog_id,
                'parent_id' => $requestData['blog_comment_id'],
                'user_id'   => $authUser ? $authUser->id : ($requestData['user_id'] ?? null),
                'name'      => $authUser ? null : ($requestData['name'] ?? null),
                'email'     => $authUser ? null : ($requestData['email'] ?? null),
                'comment'   => $requestData['comment'],
                'status'    => $requestData['status'] ?? 'active',
            ];
            
            // Generate slug if needed
            if (!isset($replyData['slug'])) {
                $replyData['slug'] = \Illuminate\Support\Str::slug($replyData['comment'] . '-' . time());
            }

            // Create reply in the unified BlogCommentModel
            if ($reply = self::$commentModel::query()->create($replyData)) {
                // Load the user relationship if it exists
                $reply->load('user');
                return messageResponse('Reply submitted successfully', $reply, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
