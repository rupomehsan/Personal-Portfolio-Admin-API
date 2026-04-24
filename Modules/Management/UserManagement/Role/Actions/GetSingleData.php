<?php

namespace Modules\Management\UserManagement\Role\Actions;



class GetSingleData
{
    static $model = \Modules\Management\UserManagement\Role\Database\Models\Model::class;

    public static function execute($slug)
    {
        try {
            $with = [];
            
            // Handle fields parameter - can be passed as fields[0], fields[1], etc.
            $fields = request()->input('fields');
            if (empty($fields) || !is_array($fields)) {
                $fields = '*'; // Default to all fields
            }
            
            if (!$data = self::$model::query()->with($with)->select($fields)->where('slug', $slug)->first()) {
                return messageResponse('Data not found...',$data, 404, 'error');
            }
            return entityResponse($data);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}