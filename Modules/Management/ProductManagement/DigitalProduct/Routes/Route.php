<?php

use Modules\Management\ProductManagement\DigitalProduct\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('digital-products')->group(function () {
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

    // Digital Product Comments Routes
    Route::prefix('digital-product-comments')->group(function () {
        Route::get('', [Controller::class, 'getAllDigitalProductComments']);
        Route::get('product/{digital_product_id}', [Controller::class, 'getDigitalProductComments']);
        Route::get('{comment_id}/replies', [Controller::class, 'getDigitalProductCommentReplies']);
        Route::post('store', [Controller::class, 'submitDigitalProductComment']);
        Route::post('reply', [Controller::class, 'submitDigitalProductCommentReply']);
        Route::post('destroy/{id}', [Controller::class, 'destroyDigitalProductComment']);
    });

    // Product Order Management Routes (Admin Only)

    Route::get('digital-product-orders', [Controller::class, 'getAllProductOrders']);
    Route::get('digital-product-orders/{order_id}', [Controller::class, 'getProductOrderById']);
    Route::patch('digital-product-orders/{order_id}/status', [Controller::class, 'updateProductOrderStatus']);
    Route::post('digital-product-orders/{order_id}/provide-access', [Controller::class, 'provideOrderAccess']);
    Route::post('digital-product-orders/{order_id}/send-reminder', [Controller::class, 'sendOrderReminder']);
    Route::post('digital-product-orders/{order_id}/notes', [Controller::class, 'addOrderNote']);
    Route::delete('digital-product-orders/{order_id}', [Controller::class, 'deleteProductOrder']);



});
