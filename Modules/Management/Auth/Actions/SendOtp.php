<?php

namespace Modules\Management\Auth\Actions;


use Modules\Mail\OTPSendMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendOtp
{
    static $userModel = \Modules\Management\UserManagement\User\Database\Models\Model::class;

    public static function execute($request)
    {
        try {

            $requestData = $request->all();
            $user = self::$userModel::where('email', $requestData['email'])->first();

            if (!$user) {
                return messageResponse('User not found please register', $requestData, 400, 'error');
            }

            $otp = self::generateOTPCode();

            $isExist = DB::table('otp_codes')->where('email', $requestData['email'])->exists();

            if ($isExist) {
                DB::table('otp_codes')->where('email', $requestData['email'])->delete();
            }

            DB::table('otp_codes')->insert([
                'email' => $requestData['email'],
                'otp' => $otp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            Mail::to($requestData['email'])->send(new OTPSendMail($otp));

            return messageResponse('OTP successfully send', [
                'email' => $requestData['email'],
                'otp' => $otp
            ]);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
    public static function generateOTPCode()
    {
        return rand(100000, 999999);
    }
}
