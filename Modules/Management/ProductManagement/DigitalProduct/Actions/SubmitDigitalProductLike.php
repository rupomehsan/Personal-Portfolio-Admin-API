<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions;

use Modules\Management\ProductManagement\DigitalProduct\Database\Models\DigitalProductLikeModel;

class SubmitDigitalProductLike
{
    public static function execute($digital_product_id)
    {
        try {
            $ipAddress = request()->ip();

            $recentLike = DigitalProductLikeModel::where('digital_product_id', $digital_product_id)
                ->where('ip', $ipAddress)
                ->where('created_at', '>=', now()->subHours(12))
                ->first();

            if ($recentLike) {
                return messageResponse('You have already liked this product. Please wait 12 hours before liking again.', [], 429, 'error');
            }

            DigitalProductLikeModel::create([
                'digital_product_id' => $digital_product_id,
                'ip'                 => $ipAddress,
            ]);

            return messageResponse('Like submitted successfully', [], 201, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
