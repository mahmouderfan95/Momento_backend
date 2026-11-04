<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomePageController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\FavoriteController;

Route::group(['prefix' => 'v1'], function () {
    #get homepage api
    Route::get('home', [HomePageController::class, 'index']);
    #get product details api
    Route::get('products/{id}',[ProductController::class, 'details']);
    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoriteController::class, 'index']);
        Route::get('count', [FavoriteController::class, 'count']);
        Route::post('{productId}/toggle', [FavoriteController::class, 'toggle'])
            ->whereNumber('productId')
            ->middleware('throttle:60,1');
    });
});
