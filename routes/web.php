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
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RefController;
use App\Http\Controllers\AdminController;

Route::middleware('auth')->group(function () {
    Route::resource('admins', AdminController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('vendors', VendorController::class);
    Route::resource('products', ProductController::class);
    Route::resource('refs', RefController::class);
    Route::patch('/refs/{id}/toggle-status', [RefController::class, 'toggleStatus'])->name('refs.toggle-status');

    // User Approvals
    Route::get('/approvals', [App\Http\Controllers\UserApprovalController::class, 'index'])->name('approvals.index');
    Route::put('/approvals/{id}/approve', [App\Http\Controllers\UserApprovalController::class, 'approve'])->name('approvals.approve');
    Route::delete('/approvals/{id}/reject', [App\Http\Controllers\UserApprovalController::class, 'reject'])->name('approvals.reject');

    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/404', function () {
    return view('pages-404');
})->name('not-found');