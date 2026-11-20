<?php

use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\RatingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomePageController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\FavoriteController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\WalletProviderController;
use App\Http\Controllers\Front\CouponController;
use App\Http\Controllers\Front\SettingController;
use App\Http\Controllers\Front\NotificationController;
use App\Http\Controllers\Front\ShippingPriceController;
use \App\Http\Controllers\Front\CityController;
Route::group(['prefix' => 'v1','middleware' => 'throttle:60,1'], function () {
    #get wallet provider
    Route::get('wallet-providers',[WalletProviderController::class,'index']);
    #get wallet numbers by provider
    Route::get('wallet-numbers/{provider}',[WalletProviderController::class, 'getWalletNumbers']);
    #get coupon value by code
    Route::get('get-valid-coupon/{code}',[CouponController::class, 'getValidCoupon']);
    #check value by coupon code
    Route::get('get-coupon-value/{code}/{cartTotal}',[CouponController::class, 'getCouponValue']);
    #get shipping cost by city_id,shipping company
    Route::get('get-shipping-cost/{city_id}',[ShippingPriceController::class, 'getShippingCost']);
    #get cities
    Route::get('cities',[CityController::class, 'getCities']);
    #get regions by city id
    Route::get('regions/{city_id}',[CityController::class, 'getRegions']);
    #get settings
    Route::get('settings',[SettingController::class,'index']);
    #
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
    #products by category id
    Route::get('category/{id}/products',[ProductController::class, 'categoryProducts']);
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
        #auth api
        Route::group(['prefix' => 'auth'], function () {
            #profile api
            Route::get('profile', [AuthController::class, 'profile']);
            #update profile api
            Route::post('update-profile', [AuthController::class, 'updateProfile']);
            #change password api
            Route::put('change-password', [AuthController::class, 'changePassword']);
        });
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
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
//            Route::get('unread-count', [NotificationController::class, 'unreadCount']);
//            Route::post('read-all', [NotificationController::class, 'markAllAsRead']);
            Route::post('{id}/read', [NotificationController::class, 'markAsRead'])->whereUuid('id');
//            Route::delete('{id}', [NotificationController::class, 'destroy'])->whereUuid('id');
        });
    });
});
