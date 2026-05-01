<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

use Modules\Management\ProductManagement\DigitalProduct\Database\Models\Model;

class ProductOrderModel extends EloquentModel
{
    protected $table = "product_orders";
    protected $guarded = [];

    public function digitalProduct()
    {
        return $this->belongsTo(Model::class, 'digital_product_id');
    }
}