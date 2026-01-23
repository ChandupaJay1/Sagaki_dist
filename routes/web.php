<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $customerCount = \App\Models\Customer::count();
    $vendorCount = \App\Models\Vendor::count();
    return view('index', compact('customerCount', 'vendorCount'));
})->name('dashboard')->middleware('auth');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;

Route::middleware('auth')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('vendors', VendorController::class);

    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/404', function () {
    return view('pages-404');
})->name('not-found');