<?php

namespace Modules\Management\BlogManagement\Blog\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class BlogCommentModel extends EloquentModel
{
    protected $table = "blog_comments";
    protected $guarded = [];
}