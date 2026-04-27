<?php

use Modules\Management\BlogManagement\Blog\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('blogs')->group(function () {
        Route::get('', [Controller::class,'index']);
        Route::get('{slug}', [Controller::class,'show']);
        Route::post('store', [Controller::class,'store']);
        Route::post('update/{slug}', [Controller::class,'update']);
        Route::post('update-status', [Controller::class,'updateStatus']);
        Route::post('soft-delete', [Controller::class,'softDelete']);
        Route::post('destroy/{slug}', [Controller::class,'destroy']);
        Route::post('restore', [Controller::class,'restore']);
        Route::post('import', [Controller::class,'import']);
        Route::post('bulk-action', [Controller::class, 'bulkAction']);
    });

    // Blog Comments Routes
    Route::prefix('blog-comments')->group(function () {
        Route::get('', [Controller::class, 'getAllComments']);
        Route::get('blog/{blog_id}', [Controller::class, 'getBlogComments']);
        Route::post('store', [Controller::class, 'submitComment']);
    });

    // Blog Comment Replies Routes
    Route::prefix('blog-comment-replies')->group(function () {
        Route::get('{comment_id}', [Controller::class, 'getCommentReplies']);
        Route::post('store', [Controller::class, 'submitCommentReply']);
    });
});