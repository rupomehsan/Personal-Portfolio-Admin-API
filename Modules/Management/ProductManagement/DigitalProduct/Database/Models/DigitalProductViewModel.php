<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class DigitalProductViewModel extends EloquentModel
{
    protected $table = "digital_product_views";
    protected $guarded = [];
}
