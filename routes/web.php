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
use App\Http\Controllers\AreaController;
use App\Http\Controllers\TerritoryController;
use App\Http\Controllers\SupplierCategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ModelMasterController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductSubCategoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\GrnReturnController;
use App\Http\Controllers\InventoryTransferController;
use App\Http\Controllers\StockAdjustmentController;

Route::get('/test-customer', function() {
    return App\Models\Customer::first();
});

Route::middleware('auth')->group(function () {
    Route::get('/master-tables', function () {
        return view('master_tables');
    })->name('master-tables');

    Route::resource('customer-categories', CustomerCategoryController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('units', UnitController::class);
    Route::resource('item-categories', ItemCategoryController::class);
    Route::resource('areas', AreaController::class);
    Route::resource('territories', TerritoryController::class);
    Route::resource('supplier-categories', SupplierCategoryController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('model-masters', ModelMasterController::class);
    Route::resource('currencies', CurrencyController::class);
    Route::resource('terms', TermController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('product-sub-categories', ProductSubCategoryController::class);
    Route::get('main-products', [ProductController::class, 'mainProducts'])->name('main-products.index');
    Route::get('main-products/create', [ProductController::class, 'createMain'])->name('main-products.create');
    Route::post('main-products', [ProductController::class, 'storeMain'])->name('main-products.store');
    Route::resource('sales-orders', SalesOrderController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('sales-returns', SalesReturnController::class);
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::resource('grns', GrnController::class);
    Route::resource('grn-returns', GrnReturnController::class);
    Route::resource('inventory-transfers', InventoryTransferController::class)->only(['index','create','store']);
    Route::patch('inventory-transfers/{id}/status', [InventoryTransferController::class, 'updateStatus'])->name('inventory-transfers.update-status');
    Route::resource('stock-adjustments', StockAdjustmentController::class)->only(['index','create','store']);

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

    // Pay Bills - Separate Routes for Supplier and Customer
    Route::get('pay-bills/supplier/create', [\App\Http\Controllers\PayBillController::class, 'createSupplier'])->name('pay-bills.supplier.create');
    Route::get('pay-bills/customer/create', [\App\Http\Controllers\PayBillController::class, 'createCustomer'])->name('pay-bills.customer.create');
    Route::get('pay-bills/{id}/print', [\App\Http\Controllers\PayBillController::class, 'print'])->name('pay-bills.print');
    Route::resource('pay-bills', \App\Http\Controllers\PayBillController::class);

    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class , 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class , 'update'])->name('profile.update');
});

Route::get('/404', function () {
    return view('pages-404');
})->name('not-found');
