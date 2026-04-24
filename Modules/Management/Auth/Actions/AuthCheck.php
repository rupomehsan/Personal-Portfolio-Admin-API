<?php

namespace Modules\Management\Auth\Actions;

use Modules\Management\UserManagement\User\Database\Models\Model as UserModel;

class AuthCheck
{
    public static function execute()
    {
        try {
            if (auth()->check()) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
