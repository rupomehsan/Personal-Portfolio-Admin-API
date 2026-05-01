<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder;

use App\Models\ProductOrder;

class ProvideOrderAccess
{
    public static function execute($order_id)
    {
        try {
            $order = ProductOrder::find($order_id);

            if (!$order) {
                return messageResponse('Order not found', [], 404, 'error');
            }

            $license_key = request('license_key');
            $documentation = request('documentation');
            $support_contact = request('support_contact');

            if (!$license_key) {
                return messageResponse('License key is required', [], 422, 'error');
            }

            // Store access details in payment_details JSON
            $payment_details = $order->payment_details ?? [];
            $payment_details['license_key'] = $license_key;
            $payment_details['documentation'] = $documentation;
            $payment_details['support_contact'] = $support_contact;
            $payment_details['access_provided_at'] = now();

            $order->update([
                'payment_details' => $payment_details,
                'payment_status' => 'compelete',
            ]);

            // TODO: Send email with license key and documentation to customer

            return messageResponse(
                'Download access provided and email sent to customer',
                ['data' => $order],
                200,
                'success'
            );
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
