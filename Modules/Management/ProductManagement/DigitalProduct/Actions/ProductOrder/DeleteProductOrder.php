<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder;

use App\Models\ProductOrder;

class DeleteProductOrder
{
    public static function execute($order_id)
    {
        try {
            $order = ProductOrder::find($order_id);

            if (!$order) {
                return messageResponse('Order not found', [], 404, 'error');
            }

            $order->delete();

            return messageResponse('Order deleted successfully', [], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
