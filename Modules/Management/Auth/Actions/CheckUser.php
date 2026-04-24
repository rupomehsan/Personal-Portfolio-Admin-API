<?php

namespace Modules\Management\Auth\Actions;



class CheckUser
{
    static $model = \Modules\Management\UserManagement\User\Database\Models\Model::class;
    public static function execute()
    {
        try {
            if (auth()->check()) {
                $user = self::$model::where('id', auth()->user()->id)
                    ->select([
                        'id',
                        'slug',
                        'name',
                        'email',
                        'image',
                        'role_id',
                    ])->with([
                        'role' => function ($query) {
                            $query->select('id', 'name', 'serial_no');
                        },
                        'address' => function ($query) {
                            $query->selectRaw("id, user_id, state, phone_number, city, post, country, address, slug, JSON_UNQUOTE(JSON_EXTRACT(phone_number, '$[0]')) as number")
                                ->where('user_id', auth()->user()->id);
                        },
                         'social_links' => function ($query) {
                            $query->select('id', 'user_id', 'media_name', 'link')
                                ->where('user_id', auth()->user()->id);
                        }
                    ])
                    ->first();
                    
                auth()->guard('web')->login($user, 1);
                
                return entityResponse($user);
            }
            return response()->json(["User not found"], 404);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
