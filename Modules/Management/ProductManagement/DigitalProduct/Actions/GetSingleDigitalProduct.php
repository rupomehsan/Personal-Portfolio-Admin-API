<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions;

use Modules\Management\ProductManagement\DigitalProduct\Database\Models\DigitalProductViewModel;
use Modules\Management\ProductManagement\DigitalProduct\Database\Models\DigitalProductLikeModel;
use Modules\Management\ProductManagement\DigitalProduct\Database\Models\DigitalProductCommentModel;

class GetSingleDigitalProduct
{
    static $model = \Modules\Management\ProductManagement\DigitalProduct\Database\Models\Model::class;

    public static function execute($slug)
    {
        try {
            $fields = request()->input('fields') ?? ['*'];
            if (!$data = self::$model::query()->select($fields)->where('slug', $slug)->first()) {
                return messageResponse('Data not found...', [], 404, 'error');
            }

            $ipAddress = request()->ip();

            $viewExists = DigitalProductViewModel::where('digital_product_id', $data->id)
                ->where('ip', $ipAddress)
                ->where('created_at', '>=', now()->subHours(1))
                ->exists();

            if (!$viewExists) {
                DigitalProductViewModel::create([
                    'digital_product_id' => $data->id,
                    'ip'                 => $ipAddress,
                ]);
            }

            $data->total_views = DigitalProductViewModel::where('digital_product_id', $data->id)->count();
            $data->total_likes = DigitalProductLikeModel::where('digital_product_id', $data->id)->count();

            $data->comments = DigitalProductCommentModel::query()
                ->with(['replies'])
                ->where('digital_product_id', $data->id)
                ->whereNull('parent_id')
                ->where('status', 'active')
                ->orderBy('id', 'desc')
                ->get();

            return entityResponse($data);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
