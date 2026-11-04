<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomePageController;

Route::group(['prefix' => 'v1'], function () {
    #get homepage api
    Route::get('home', [HomePageController::class, 'index']);
});
