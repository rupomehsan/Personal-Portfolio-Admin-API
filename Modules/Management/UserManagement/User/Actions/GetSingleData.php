<?php

namespace Modules\Management\UserManagement\User\Actions;



class GetSingleData
{
    static $model = \Modules\Management\UserManagement\User\Database\Models\Model::class;

    public static function execute($slug)
    {
        try {
            $with = ['address', 'socialLinks', 'logs','role'];
            $fields = request()->input('fields') ?? ['*'];
            if (!$data = self::$model::query()->with($with)->select($fields)->where('slug', $slug)->first()) {
                return messageResponse('Data not found...',$data, 404, 'error');
            } 
            return entityResponse($data);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}