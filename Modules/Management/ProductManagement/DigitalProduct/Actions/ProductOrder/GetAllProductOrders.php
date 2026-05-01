<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder;

use Modules\Management\ProductManagement\DigitalProduct\Database\Models\ProductOrderModel as ProductOrder;

class GetAllProductOrders
{
    public static function execute()
    {
        try {
            $search = request('search');
            $status = request('status');
            $per_page = request('per_page', 10);

            $query = ProductOrder::with('digitalProduct');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                      ->orWhere('last_name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%")
                      ->orWhere('trx_number', 'like', "%$search%");
                });
            }

            if ($status) {
                $query->where('payment_status', $status);
            }

            $orders = $query->orderBy('created_at', 'desc')->paginate($per_page);

            return messageResponse('Orders fetched successfully', ['data' => $orders], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
