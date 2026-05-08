<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/customers/{id}', [CustomerController::class, 'show']);
Route::get('/vendors/{id}', [VendorController::class, 'show']);
Route::get('/vendors/{id}/outstanding-bills', [VendorController::class, 'getOutstandingBills']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/{id}/stock', [ProductController::class, 'stock']);

Route::prefix('v1')->name('api.')->group(function () {

    // ==================== PUBLIC ROUTES ====================
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify-serial', [AuthController::class, 'verifySerial']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // ==================== PROTECTED ROUTES ====================
    Route::middleware('auth:sanctum')->group(function () {

        // Auth routes
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::put('/update-profile', [AuthController::class, 'updateProfile']);



        // Resource routes
        Route::apiResource('products', ProductController::class);
        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('vendors', VendorController::class);
    });

});