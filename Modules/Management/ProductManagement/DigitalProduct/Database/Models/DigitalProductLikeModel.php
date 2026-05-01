<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class DigitalProductLikeModel extends EloquentModel
{
    protected $table = "digital_product_likes";
    protected $guarded = [];
}
