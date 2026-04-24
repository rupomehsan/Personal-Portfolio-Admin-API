<?php

namespace Modules\Management\Auth\Controller;

use Modules\Management\Auth\Actions\Login;
use Modules\Management\Auth\Actions\Register;
use Modules\Management\Auth\Actions\VerifyOtp;
use Modules\Management\Auth\Actions\SendOtp;
use Modules\Management\Auth\Actions\CheckUser;
use Modules\Management\Auth\Actions\AuthCheck;
use Modules\Management\Auth\Actions\ResetPassword;

use Modules\Management\Auth\Validations\LoginValidation;
use Modules\Management\Auth\Validations\RegisterValidation;
use Modules\Management\Auth\Validations\OTPValidation;
use Modules\Management\Auth\Validations\SendOTPValidation;

use App\Http\Controllers\Controller as ControllersController;

class Controller extends ControllersController
{

    public function login(LoginValidation $request)
    {
        $data = Login::execute($request);
        return $data;
    }
    public function register(RegisterValidation $request)
    {
        $data = Register::Register($request);
        return $data;
    }
    public function SendOtp(SendOTPValidation $request)
    {
        $data = SendOtp::execute($request);
        return $data;
    }
    public function VerifyOtp()
    {
        $data = VerifyOtp::execute();
        return $data;
    }

    public function checkUser()
    {
        $data = CheckUser::execute();
        return $data;
    }
    public function authCheck()
    {
        $data = AuthCheck::execute();
        return $data;
    }
    public function ResetPassword(LoginValidation $request)
    {
        $data = ResetPassword::execute($request);
        return $data;
    }
}
