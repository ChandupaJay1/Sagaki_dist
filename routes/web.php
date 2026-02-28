<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $customerCount = \App\Models\Customer::count();
    $vendorCount = \App\Models\Vendor::count();
    $productCount = \App\Models\Product::count();
    return view('index', compact('customerCount', 'vendorCount', 'productCount'));
})->name('dashboard')->middleware('auth');

Route::get('/login', [AuthController::class , 'showLogin'])->name('login');
Route::post('/login', [AuthController::class , 'login']);

Route::get('/register', [AuthController::class , 'showRegister'])->name('register');
Route::post('/register', [AuthController::class , 'register']);

Route::post('/logout', [AuthController::class , 'logout'])->name('logout');

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RefController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\CustomerCategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ItemCategoryController;

Route::middleware('auth')->group(function () {
    Route::get('/master-tables', function () {
        return view('master_tables');
    })->name('master-tables');

    Route::resource('customer-categories', CustomerCategoryController::class);
    Route::resource('units', UnitController::class);
    Route::resource('item-categories', ItemCategoryController::class);

    Route::resource('admins', AdminController::class);
    Route::resource('routes', RouteController::class);
    Route::post('routes/{route}/assign-customer', [RouteController::class, 'assignCustomer'])->name('routes.assign-customer');
    Route::delete('routes/{route}/customers/{customer}', [RouteController::class, 'unassignCustomer'])->name('routes.unassign-customer');
    Route::post('routes/{route}/assign-ref', [RouteController::class, 'assignRef'])->name('routes.assign-ref');
    Route::delete('routes/{route}/refs/{ref}', [RouteController::class, 'unassignRef'])->name('routes.unassign-ref');
    Route::patch('customers/{customer}/route', [CustomerController::class, 'updateRoute'])->name('customers.update-route');
    Route::patch('refs/{ref}/route', [RefController::class, 'updateRoute'])->name('refs.update-route');
    Route::resource('customers', CustomerController::class);
    Route::resource('vendors', VendorController::class);
    Route::resource('products', ProductController::class);
    Route::resource('refs', RefController::class);
    Route::patch('/refs/{id}/toggle-status', [RefController::class , 'toggleStatus'])->name('refs.toggle-status');

    // User Approvals
    Route::get('/approvals', [App\Http\Controllers\UserApprovalController::class , 'index'])->name('approvals.index');
    Route::put('/approvals/{id}/approve', [App\Http\Controllers\UserApprovalController::class , 'approve'])->name('approvals.approve');
    Route::delete('/approvals/{id}/reject', [App\Http\Controllers\UserApprovalController::class , 'reject'])->name('approvals.reject');
    Route::get('/approvals/count', [App\Http\Controllers\UserApprovalController::class , 'count'])->name('approvals.count');

    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class , 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class , 'update'])->name('profile.update');
});

Route::get('/404', function () {
    return view('pages-404');
})->name('not-found');
