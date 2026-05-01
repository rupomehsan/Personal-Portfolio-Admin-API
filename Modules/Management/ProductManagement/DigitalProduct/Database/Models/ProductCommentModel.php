<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class ProductCommentModel extends EloquentModel
{
    protected $table = "product_comments";
    protected $guarded = [];
}