<?php

namespace Modules\Management\BlogManagement\Blog\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

use Modules\Management\BlogManagement\Blog\Database\Models\Model as BlogModel;
use Modules\Management\UserManagement\User\Database\Models\Model as UserModel;
class BlogCommentModel extends EloquentModel
{
    protected $table = "blog_comments";
    protected $guarded = [];

    public function blog()
    {
        return $this->belongsTo(BlogModel::class, 'blog_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}