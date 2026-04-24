<?php

namespace Modules\Management\BlogManagement\Blog\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class BlogViewModel extends EloquentModel
{
    protected $table = "blog_views";
    protected $guarded = [];
}