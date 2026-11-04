<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomePageController;
use App\Http\Controllers\Front\ProductController;

Route::group(['prefix' => 'v1'], function () {
    #get homepage api
    Route::get('home', [HomePageController::class, 'index']);
    #get product details api
    Route::get('products/{id}',[ProductController::class, 'details']);
});
