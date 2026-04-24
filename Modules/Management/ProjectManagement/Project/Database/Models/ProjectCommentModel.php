<?php

namespace Modules\Management\ProjectManagement\Project\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class ProjectCommentModel extends EloquentModel
{
    protected $table = "project_comments";
    protected $guarded = [];
}