<?php

use App\Http\Controllers\Front\RatingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomePageController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\FavoriteController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\AuthController;

Route::group(['prefix' => 'v1','middleware' => 'throttle:60,1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        #register api
        Route::post('register',[AuthController::class,'register']);
        #login api
        Route::post('login',[AuthController::class,'login']);
    });
    #get homepage api
    Route::get('home', [HomePageController::class, 'index']);
    #get product details api
    Route::get('products/{id}',[ProductController::class, 'details']);
    #fav apis
    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoriteController::class, 'index']);
        Route::get('count', [FavoriteController::class, 'count']);
        Route::post('{productId}/toggle', [FavoriteController::class, 'toggle'])
            ->whereNumber('productId');
    });
    #cart
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'show']);
        Route::post('items', [CartController::class, 'store'])->middleware('throttle:60,1');
        Route::put('items/{itemId}', [CartController::class, 'update'])->whereNumber('itemId');
        Route::delete('items/{itemId}', [CartController::class, 'destroy'])->whereNumber('itemId');
        Route::delete('/', [CartController::class, 'clear']);
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('products/{productId}/ratings',[RatingController::class,'store']);
    });
});
