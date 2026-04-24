<?php

namespace Modules\Management\Auth\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPassword
{
    static $model = \Modules\Management\UserManagement\User\Database\Models\Model::class;

    public static function execute($request)
    {
        try {
            $user = self::$model::where('status', 'active')->where("email", request()->email)->first();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Sorry,user not found'], 404);
            }

            $user->password = Hash::make($request->password);
            $user->save();
            return messageResponse('Password Reset Successfully', $user, 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
