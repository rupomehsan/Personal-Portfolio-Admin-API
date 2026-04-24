<?php

use Illuminate\Support\Facades\Route;
use Modules\Controllers\Backend\BackendController;

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin', [BackendController::class, 'AdminPanel'])->name('admin.dashboard');
