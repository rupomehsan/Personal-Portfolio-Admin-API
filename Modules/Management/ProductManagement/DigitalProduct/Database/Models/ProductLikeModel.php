<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class ProductLikeModel extends EloquentModel
{
    protected $table = "product_likes";
    protected $guarded = [];
}