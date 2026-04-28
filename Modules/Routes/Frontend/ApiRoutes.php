<?php

use Modules\Management\BlogManagement\Blog\Controller\Controller as BlogController;
use Modules\Management\ProjectManagement\Project\Controller\Controller as ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('get-all-projects', [ProjectController::class,'index']);
Route::get('get-single-projects/{slug}', [ProjectController::class,'getSingleProject']);
Route::get('get-projects-comments/{project_id}', [ProjectController::class,'getProjectComments']);
Route::post('submit-project-comment/{project_id}', [ProjectController::class,'submitProjectComment']);
Route::post('submit-project-like/{project_id}', [ProjectController::class,'submitProjectLike']);

Route::get('get-all-blogs', [BlogController::class,'index']);
 Route::get('get-all-comments-by-blog/{slug}', [BlogController::class,'getBlogComments']);
