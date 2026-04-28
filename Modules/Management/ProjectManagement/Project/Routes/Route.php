<?php

use Modules\Management\ProjectManagement\Project\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('projects')->group(function () {
        Route::get('', [Controller::class, 'index']);
        Route::get('{slug}', [Controller::class, 'show']);
        Route::post('store', [Controller::class, 'store']);
        Route::post('update/{slug}', [Controller::class, 'update']);
        Route::post('update-status', [Controller::class, 'updateStatus']);
        Route::post('soft-delete', [Controller::class, 'softDelete']);
        Route::post('destroy/{slug}', [Controller::class, 'destroy']);
        Route::post('restore', [Controller::class, 'restore']);
        Route::post('import', [Controller::class, 'import']);
        Route::post('bulk-action', [Controller::class, 'bulkAction']);
    });

    // Project Comments Routes
    Route::prefix('project-comments')->group(function () {
        Route::get('', [Controller::class, 'getAllProjectComments']);
        Route::get('project/{project_id}', [Controller::class, 'getProjectComments']);
        Route::get('{comment_id}/replies', [Controller::class, 'getProjectCommentReplies']);
        Route::post('store', [Controller::class, 'submitProjectComment']);
        Route::post('reply', [Controller::class, 'submitProjectCommentReply']);
        Route::post('destroy/{id}', [Controller::class, 'destroyProjectComment']);
    });

});
