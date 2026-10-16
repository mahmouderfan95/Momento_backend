<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeOptionController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuthController;



Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');

    Route::middleware(['auth:sanctum','admin'])->group(function () {
        #logout request
        Route::post('logout', [AuthController::class, 'logout']);
        #profile
        Route::get('me', [AuthController::class, 'me']);
        #categories
        Route::apiResource('categories', CategoryController::class);
        #roles and permissions
        Route::apiResource('roles', RoleController::class);
        #admins
        Route::apiResource('admins', AdminController::class);
        #attributes
        Route::apiResource('attributes', AttributeController::class);
        #attributeOption
        Route::apiResource('attribute-options', AttributeOptionController::class);
        #settings
        #products
    });
});
