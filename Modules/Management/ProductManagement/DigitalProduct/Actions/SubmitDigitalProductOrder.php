<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions;

use App\Models\ProductOrder;

class SubmitDigitalProductOrder
{
    public static function execute($request)
    {
        try {
            // Check if transaction already exists
            $existingOrder = ProductOrder::where('trx_number', $request->trx_number)->first();
            if ($existingOrder) {
                return messageResponse('This transaction ID already exists', [], 409, 'error');
            }

            // Create the order
            $order = ProductOrder::create([
                'digital_product_id' => $request->digital_product_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'trx_number' => $request->trx_number,
                'payment_details' => $request->payment_details ? json_encode($request->payment_details) : null,
                'payment_status' => $request->payment_status ?? 'pending',
            ]);

            // TODO: Send confirmation email to customer
            // TODO: Send order notification to admin

            return messageResponse(
                'Order submitted successfully',
                [
                    'order_id' => $order->id,
                    'trx_number' => $order->trx_number,
                    'email' => $order->email,
                ],
                201,
                'success'
            );
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
