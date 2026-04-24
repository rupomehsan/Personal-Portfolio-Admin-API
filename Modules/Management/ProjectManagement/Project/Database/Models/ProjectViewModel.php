<?php

namespace Modules\Management\ProjectManagement\Project\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class ProjectViewModel extends EloquentModel
{
    protected $table = "project_views";
    protected $guarded = [];
}