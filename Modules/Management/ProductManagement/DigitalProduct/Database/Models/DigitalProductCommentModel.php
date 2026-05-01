<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class DigitalProductCommentModel extends EloquentModel
{
    use SoftDeletes;

    protected $table = "digital_product_comments";
    protected $guarded = [];

    public function digitalProduct()
    {
        return $this->belongsTo(Model::class, 'digital_product_id', 'id');
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
