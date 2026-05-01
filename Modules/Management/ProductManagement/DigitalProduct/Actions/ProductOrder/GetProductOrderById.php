<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder;

use Modules\Management\ProductManagement\DigitalProduct\Database\Models\ProductOrderModel as ProductOrder;

class GetProductOrderById
{
    public static function execute($order_id)
    {
        try {
            $order = ProductOrder::with('digitalProduct')->find($order_id);

            if (!$order) {
                return messageResponse('Order not found', [], 404, 'error');
            }

            return messageResponse('Order fetched successfully', ['data' => $order], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
