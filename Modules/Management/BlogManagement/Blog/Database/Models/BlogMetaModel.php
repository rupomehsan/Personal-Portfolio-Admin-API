<?php

namespace Modules\Management\BlogManagement\Blog\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class BlogMetaModel extends EloquentModel
{
    protected $table = "blog_metas";
    protected $guarded = [];
}