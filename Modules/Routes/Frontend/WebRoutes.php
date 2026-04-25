<?php

use Illuminate\Support\Facades\Route;
use Modules\Controllers\Frontend\Auth\AuthController;



/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/',function () {
    return redirect()->route('LoginPage');
});

Route::get('/login', [AuthController::class, 'LoginPage'])->name('LoginPage');
Route::get('/forgot-password', [AuthController::class, 'ForgotPassword'])->name('ForgotPassword');



