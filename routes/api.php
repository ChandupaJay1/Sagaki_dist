<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MobileController;
use Illuminate\Support\Facades\Route;

Route::get('/customers/{id}', [CustomerController::class, 'show']);
Route::get('/customers/{id}/outstanding-invoices', [CustomerController::class, 'getOutstandingInvoices']);
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
    Route::get('/route-customers/{id}', [MobileController::class, 'routeCustomersByRouteId']);

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

        // ==================== MOBILE ROUTES (For Ref App) ====================
        Route::get('/my-route', [MobileController::class, 'myRoute']);
        Route::put('/my-route', [MobileController::class, 'updateMyRoute']);
        Route::get('/my-route/customers', [MobileController::class, 'myRouteCustomers']);
        Route::get('/areas', [MobileController::class, 'areas']);
        Route::get('/territories', [MobileController::class, 'territories']);
        Route::get('/routes', [MobileController::class, 'routes']);
        Route::get('/routes/{id}/customers', [MobileController::class, 'routeCustomers']);
    });

});