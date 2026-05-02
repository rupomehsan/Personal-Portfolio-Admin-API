<?php

namespace Modules\Management\BlogManagement\Blog\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class BlogLikeModel extends EloquentModel
{
    protected $table = "blog_likes";
    protected $guarded = [];
}
