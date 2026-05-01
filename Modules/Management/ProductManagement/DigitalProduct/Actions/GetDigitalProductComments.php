<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions;

class GetDigitalProductComments
{
    static $model = \Modules\Management\ProductManagement\DigitalProduct\Database\Models\DigitalProductCommentModel::class;

    public static function execute($digital_product_id)
    {
        try {
            $pageLimit     = request()->input('limit') ?? 10;
            $orderByColumn = request()->input('sort_by_col') ?? 'id';
            $orderByType   = request()->input('sort_type') ?? 'desc';
            $status        = request()->input('status') ?? 'active';

            $data = self::$model::query()
                ->with(['digitalProduct:id,title', 'user:id,name', 'replies.user:id,name'])
                ->where('digital_product_id', $digital_product_id)
                ->whereNull('parent_id')
                ->where('status', $status)
                ->orderBy($orderByColumn, $orderByType)
                ->paginate($pageLimit);

            return entityResponse([
                ...$data->toArray(),
                'total_comments'    => self::$model::where('digital_product_id', $digital_product_id)->whereNull('parent_id')->count(),
                'approved_comments' => self::$model::where('digital_product_id', $digital_product_id)->whereNull('parent_id')->where('status', 'active')->count(),
            ]);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
