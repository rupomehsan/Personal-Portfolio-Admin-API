<?php

namespace Modules\Management\BlogManagement\Blog\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class BlogCommentReplyModel extends EloquentModel
{
    protected $table = "blog_comment_replies";
    protected $guarded = [];
}