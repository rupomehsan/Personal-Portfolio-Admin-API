<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;

    protected $table = 'product_orders';

    protected $fillable = [
        'digital_product_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'trx_number',
        'payment_details',
        'payment_status',
    ];

    protected $casts = [
        'payment_details' => 'json',
    ];

    public function digitalProduct()
    {
        return $this->belongsTo(\Modules\Management\ProductManagement\DigitalProduct\Models\DigitalProduct::class, 'digital_product_id');
    }
}
