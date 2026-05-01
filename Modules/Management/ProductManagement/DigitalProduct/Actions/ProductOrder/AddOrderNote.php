<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder;

use App\Models\ProductOrder;

class AddOrderNote
{
    public static function execute($order_id)
    {
        try {
            $order = ProductOrder::find($order_id);

            if (!$order) {
                return messageResponse('Order not found', [], 404, 'error');
            }

            $note = request('note');
            if (!$note) {
                return messageResponse('Note is required', [], 422, 'error');
            }

            // Store note in payment_details
            $payment_details = $order->payment_details ?? [];
            if (!isset($payment_details['notes'])) {
                $payment_details['notes'] = [];
            }

            $payment_details['notes'][] = [
                'admin_name' => auth()->user()->name ?? 'Admin',
                'note' => $note,
                'created_at' => now(),
            ];

            $order->update(['payment_details' => $payment_details]);

            return messageResponse('Note added successfully', ['data' => $order], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
