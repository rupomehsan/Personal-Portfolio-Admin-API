<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

class SubmitCommentReply
{
    static $commentModel = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentModel::class;
    static $replyModel = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentReplyModel::class;
    static $junctionModel = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentBlogCommentReplyModel::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            // Create reply
            if ($reply = self::$replyModel::query()->create($requestData)) {
                
                // Create junction record if blog_comment_id exists
                if (isset($requestData['blog_comment_id']) && $requestData['blog_comment_id']) {
                    self::$junctionModel::query()->create([
                        'blog_comment_id' => $requestData['blog_comment_id'],
                        'blog_comment_reply_id' => $reply->id,
                        'creator' => auth()->id() ?? $requestData['creator'] ?? null,
                        'status' => 'active',
                    ]);
                }

                return messageResponse('Reply submitted successfully', $reply, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
