<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\CustomerProfileController;


Route::get('/', function () {
    return view('welcome');
});

// routes/web.php
Route::get('/admin/test-layout', function () {
    return view('layouts.admin');
});

Route::get('/front/test-layout', function () {
    return view('layouts.front');
});

// Route yang dilindungi middleware (harus login dulu)
Route::middleware(['auth', 'role:administrator,pegawai'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    // Route CategoryController
    Route::resource('category', CategoryController::class);
    // Route PurchaseOrderController
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::post('purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
    Route::post('purchase-orders/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'cancel'])->name('purchase-orders.cancel');
    Route::resource('purchases', PurchaseController::class)->only(['index']);
    Route::post('purchases/{purchaseOrder}/complete', [PurchaseController::class, 'complete'])->name('purchases.complete');
    Route::get('purchase-orders/{purchaseOrder}/receive', [PurchaseController::class, 'create'])->name('purchase-orders.receive');
    Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseController::class, 'store'])->name('purchase-orders.receive.store');

    // Route ProductController
    Route::resource('product', ProductController::class);
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Route Khusus Customer (Akses belanja & histori pesanan)
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard',
        [CustomerDashboardController::class,'index'])
        ->name('customer.dashboard');

    Route::get('/customer/orders',
        [CustomerOrderController::class,'index'])
        ->name('customer.orders.index');

    Route::get('/customer/orders/checkout',
        [CustomerOrderController::class,'showCheckout'])
        ->name('customer.orders.checkout.show');

    Route::post('/customer/orders/checkout',
        [CustomerOrderController::class,'checkout'])
        ->name('customer.orders.checkout');

    Route::get('/customer/orders/{id}',
        [CustomerOrderController::class,'show'])
        ->name('customer.orders.show');

    Route::get('/shop',
        [CustomerProductController::class,'index'])
        ->name('customer.products.index');

    Route::get('/shop/{id}',
        [CustomerProductController::class,'show'])
        ->name('customer.products.show');

    Route::post(
    '/orders/{id}/received',
        [CustomerOrderController::class, 'confirmReceived']
    )->name('customer.orders.received');

    Route::get('/profile', [CustomerProfileController::class, 'edit'])
    ->name('customer.profile.edit');

    Route::post('/profile', [CustomerProfileController::class, 'update'])
        ->name('customer.profile.update');
});