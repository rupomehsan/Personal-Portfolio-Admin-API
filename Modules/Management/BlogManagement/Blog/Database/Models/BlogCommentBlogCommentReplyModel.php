<?php

namespace Modules\Management\BlogManagement\Blog\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class BlogCommentBlogCommentReplyModel extends EloquentModel
{
    protected $table = "blog_comment_blog_comment_replies";
    protected $guarded = [];
}