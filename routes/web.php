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

// Route khusus admin
Route::middleware(['auth', 'role:administrator'])->group(function () {
    
    // CRUD Manajemen User
    Route::resource('users', \App\Http\Controllers\UserController::class);
    // Route CategoryController -> Kategori hanya bisa ditambahkan oleh admin
    Route::resource('category', CategoryController::class);
    // CRUD Manajemen Supplier
    Route::resource('supplier', \App\Http\Controllers\SupplierController::class);
    
});
// Route yang dilindungi middleware (hanya bisa diakses oleh administrator dan pegawai)
Route::middleware(['auth', 'role:administrator,pegawai'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    
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

    // Route Admin Order (Penjualan - Modul 3)
    Route::get('admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('admin/orders/{id}/process', [AdminOrderController::class, 'process'])->name('admin.orders.process');
    Route::post('admin/orders/{id}/ship', [AdminOrderController::class, 'ship'])->name('admin.orders.ship');

    // Route Reports & Rekapitulasi (Modul 1 Tambahan)
    Route::get('admin/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('admin/reports/pdf', [\App\Http\Controllers\ReportController::class, 'exportPdf'])->name('admin.reports.pdf');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'storeRegister'])->middleware('throttle:5,1')->name('register.post'); // throttle:5,1 membatasi 5 kali percobaan dalam 1 menit
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

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
Route::get('/tes-bocor', function () {
    // Memaksa Laravel memanggil tabel yang tidak pernah ada
    return \Illuminate\Support\Facades\DB::select('SELECT * FROM tabel_hantu_awkwkwk'); 
});