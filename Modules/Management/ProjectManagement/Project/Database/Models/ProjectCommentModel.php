<?php

namespace Modules\Management\ProjectManagement\Project\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Modules\Management\ProjectManagement\Project\Database\Models\ProjectCommentReplyModel;
class ProjectCommentModel extends EloquentModel
{
    protected $table = "project_comments";
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Model::class, 'project_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(
            \Modules\Management\UserManagement\User\Database\Models\Model::class,
            'user_id',
            'id'
        );
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->with('replies');
    }
}
