<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder;

use App\Models\ProductOrder;

class UpdateProductOrderStatus
{
    public static function execute($order_id)
    {
        try {
            $order = ProductOrder::find($order_id);

            if (!$order) {
                return messageResponse('Order not found', [], 404, 'error');
            }

            $status = request('payment_status');
            if (!in_array($status, ['pending', 'due', 'compelete'])) {
                return messageResponse('Invalid payment status', [], 422, 'error');
            }

            $order->update(['payment_status' => $status]);

            // TODO: Send email notification when status changes

            return messageResponse('Order status updated successfully', ['data' => $order], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
