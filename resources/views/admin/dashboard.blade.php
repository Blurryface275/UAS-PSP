@extends('layouts.admin')

@section('title', 'Sistem Kemudi Utama')

@section('content')
<h1 class="mt-4">Sistem Back-Office</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Rangkuman Aktivitas Toko</li>
</ol>

<!-- Barisan Kartu Statistik (4 Kolom Terpisah) -->
<div class="row">
    <!-- Kartu Biru: Kategori -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4 shadow border-0">
            <div class="card-body fw-bold fs-5">
                <i class="fas fa-tags me-2"></i> Total Kategori
                <div class="mt-2 fs-2">{{ $categoriesCount }}</div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between border-0" style="background-color: rgba(0,0,0,0.1);">
                <a class="small text-white stretched-link text-decoration-none" href="{{ route('category.index') }}">Kelola Kategori &rarr;</a>
            </div>
        </div>
    </div>
    
    <!-- Kartu Hijau: Produk -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4 shadow border-0">
            <div class="card-body fw-bold fs-5">
                <i class="fas fa-box-open me-2"></i> Total Produk
                <div class="mt-2 fs-2">{{ $productsCount }}</div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between border-0" style="background-color: rgba(0,0,0,0.1);">
                <a class="small text-white stretched-link text-decoration-none" href="#">Kelola Produk &rarr;</a>
            </div>
        </div>
    </div>
    
    <!-- Kartu Kuning: User Admin -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-dark mb-4 shadow border-0">
            <div class="card-body fw-bold fs-5">
                <i class="fas fa-users-cog me-2"></i> Total Sistem User
                <div class="mt-2 fs-2">{{ $usersCount }}</div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between border-0" style="background-color: rgba(0,0,0,0.1);">
                <a class="small text-dark stretched-link text-decoration-none" href="#">Hak Akses &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Kartu Merah: Pesanan Masuk (Modul 3) -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white mb-4 shadow border-0">
            <div class="card-body fw-bold fs-5">
                <i class="fas fa-shopping-cart me-2"></i> Pending Sales
                <div class="mt-2 fs-2">{{ $salesCount }}</div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between border-0" style="background-color: rgba(0,0,0,0.1);">
                <a class="small text-white stretched-link text-decoration-none" href="#">Proses Transaksi &rarr;</a>
            </div>
        </div>
    </div>
</div>

<!-- Data Table Preview -->
<div class="row mt-2">
    <!-- Tabel Kategori Terbaru -->
    <div class="col-xl-6">
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-tags me-1 text-primary"></i>
                5 Kategori Baru Saja Dibuat
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 border-0">Nama Kategori</th>
                                <th class="border-0">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentCategories as $cat)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $cat->name }}</td>
                                <td class="text-muted"><small>{{ Str::limit($cat->description, 35) }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 text-end">
                <a href="{{ route('category.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Kategori</a>
            </div>
        </div>
    </div>

    <!-- Tabel Produk Terbaru -->
    <div class="col-xl-6">
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-box-open me-1 text-success"></i>
                Preview 5 Produk Terakhir
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 border-0">Produk</th>
                                <th class="border-0">Harga</th>
                                <th class="text-center border-0">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProducts as $prod)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $prod->name }}</td>
                                <!-- Format angka rupiah pakai number_format -->
                                <td class="text-success fw-bold">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge @if($prod->stock > 50) bg-success @else bg-warning text-dark @endif rounded-pill">{{ $prod->stock }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 text-end">
                <!-- Nanti tombol ini nyambung ke ProductController milikmu! -->
                <a href="#" class="btn btn-sm btn-outline-success">Ke Menu Master Produk</a>
            </div>
        </div>
    </div>
</div>
@endsection
