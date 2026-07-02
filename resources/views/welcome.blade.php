@extends('layouts.front')

@section('title', 'Katalog Utama Toko Kita')

@section('content')
<div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-3 row-cols-xl-4 justify-content-center">
    
    <!-- (Ini adalah cangkang Dummy sementara. Kelak oleh Kenny (Orang ke-3) 
         bagian ini akan di-Loop (foreach) menggunakan data asli dari tabel Products) -->
    
    <!-- Contoh Card Barang Pertama -->
    <div class="col mb-5">
        <div class="card h-100 shadow-sm border-0">
            <!-- Product image (Dummy 450x300) -->
            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="Foto Barang" />
            
            <!-- Product details-->
            <div class="card-body p-4">
                <div class="text-center">
                    <!-- Product name-->
                    <h5 class="fw-bolder">Sepatu Keren</h5>
                    <!-- Deskripsi Singkat -->
                    <p class="text-muted small mb-2">Pakaian Pria</p>
                    <!-- Product price-->
                    <span class="fs-5 fw-bold text-success">Rp 250.000</span>
                </div>
            </div>
            <!-- Product actions-->
            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                <div class="text-center">
                    <!-- Tombol Keranjang Belum Aktif -->
                    <a class="btn btn-outline-dark mt-auto w-100" href="#"><i class="bi-cart-fill me-1"></i> Beli Sekarang</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contoh Card Barang Kedua (Ada Diskon) -->
    <div class="col mb-5">
        <div class="card h-100 shadow-sm border-0">
            <!-- Sale badge-->
            <div class="badge bg-danger text-white position-absolute" style="top: 0.5rem; right: 0.5rem">PROMO</div>
            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
            <div class="card-body p-4">
                <div class="text-center">
                    <h5 class="fw-bolder">Laptop Super Cepat</h5>
                    <p class="text-muted small mb-2">Elektronik</p>
                    <span class="text-muted text-decoration-line-through me-2">Rp 10.000.000</span>
                    <br>
                    <span class="fs-5 fw-bold text-success">Rp 8.500.000</span>
                </div>
            </div>
            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                <div class="text-center">
                    <a class="btn btn-outline-dark mt-auto w-100" href="#"><i class="bi-cart-fill me-1"></i> Beli Sekarang</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection