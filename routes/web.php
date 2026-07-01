<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\OrderController;


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
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('orders.checkout.show');

// Route Khusus Customer (Akses belanja & histori pesanan)
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.tracking');
});

// Route Khusus Admin/Pegawai (Akses memproses pesanan)
Route::middleware(['auth', 'role:administrator,pegawai'])->group(function () {
    // Tambahkan di dalam grup middleware yang sudah ada
    Route::get('/admin/orders', [OrderController::class, 'adminIndex'])->name('admin.orders.index');
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});