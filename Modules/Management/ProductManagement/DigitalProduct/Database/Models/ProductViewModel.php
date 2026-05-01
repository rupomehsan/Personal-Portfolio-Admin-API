<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class ProductViewModel extends EloquentModel
{
    protected $table = "product_views";
    protected $guarded = [];
}