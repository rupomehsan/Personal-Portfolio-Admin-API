<?php

use Modules\Management\BlogManagement\Blog\Controller\Controller as BlogController;
use Modules\Management\ProjectManagement\Project\Controller\Controller as ProjectController;
use Modules\Management\ProductManagement\DigitalProduct\Controller\Controller as DigitalProductController;
use Illuminate\Support\Facades\Route;

Route::get('get-all-projects', [ProjectController::class,'index']);
Route::get('get-single-projects/{slug}', [ProjectController::class,'getSingleProject']);
Route::get('get-projects-comments/{project_id}', [ProjectController::class,'getProjectComments']);
Route::post('submit-project-comment/{project_id}', [ProjectController::class,'submitProjectComment']);
Route::post('submit-project-like/{project_id}', [ProjectController::class,'submitProjectLike']);





Route::get('get-all-digital-products', [DigitalProductController::class, 'index']);
Route::get('get-single-digital-product/{slug}', [DigitalProductController::class, 'getSingleDigitalProduct']);
Route::get('get-digital-product-comments/{digital_product_id}', [DigitalProductController::class, 'getDigitalProductComments']);
Route::post('submit-digital-product-comment/{digital_product_id}', [DigitalProductController::class, 'submitDigitalProductComment']);
Route::post('submit-digital-product-like/{digital_product_id}', [DigitalProductController::class, 'submitDigitalProductLike']);
Route::post('submit-digital-product-order', [DigitalProductController::class, 'submitDigitalProductOrder']);




Route::get('get-all-blogs', [BlogController::class,'index']);
Route::get('get-single-blog/{slug}', [BlogController::class,'getSingleBlog']);
Route::get('get-blog-categories', [BlogController::class,'getBlogCategories']);
Route::post('post-blog-comment', [BlogController::class,'submitComment']);
Route::get('get-all-comments-by-blog/{slug}', [BlogController::class,'getBlogComments']);
Route::post('submit-blog-like/{blog_id}', [BlogController::class,'submitBlogLike']);
