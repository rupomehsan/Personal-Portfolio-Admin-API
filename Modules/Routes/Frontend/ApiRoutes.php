<?php

use Modules\Management\BlogManagement\Blog\Controller\Controller as BlogController;
use Modules\Management\ProjectManagement\Project\Controller\Controller as ProjectController;
use Illuminate\Support\Facades\Route;

 Route::get('get-all-blogs', [BlogController::class,'index']);
 Route::get('get-all-projects', [ProjectController::class,'index']);