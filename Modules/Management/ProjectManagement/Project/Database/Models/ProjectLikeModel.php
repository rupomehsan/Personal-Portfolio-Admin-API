<?php

namespace Modules\Management\ProjectManagement\Project\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class ProjectLikeModel extends EloquentModel
{
    protected $table = "project_likes";
    protected $guarded = [];
}