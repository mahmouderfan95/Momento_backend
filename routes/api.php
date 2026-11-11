<?php

use App\Http\Controllers\Front\OrderController;
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
    #checkout order
    Route::post('orders', [OrderController::class, 'checkout']);
    #auth routes
    Route::group(['middleware' => 'auth:sanctum'], function () {
        #product rating
        Route::post('products/{productId}/ratings',[RatingController::class,'store']);
        #orders
        Route::group(['prefix' => 'orders'], function () {
            #get all orders
           Route::get('/', [OrderController::class, 'index']);
           #show order details
           Route::get('{orderNumber}', [OrderController::class, 'show']);
           #cancel order
           Route::post('{orderNumber}/cancel', [OrderController::class, 'cancel']);
        });
    });
});
