@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
<h1 class="mt-4">Data Produk</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Produk</li>
</ol>

@if(session('success'))
<div class="alert alert-success fw-bold">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger fw-bold">{{ session('error') }}</div>
@endif

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Daftar Produk
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $products->firstItem() + $loop->index }}</td>
                    <td class="fw-bold">{{ $product->name }}</td>
                    <!-- Gunakan categories->first() karena relasinya many-to-many (Pivot) -->
                    <td><span class="badge bg-secondary">{{ $product->categories->first()->name ?? 'Tanpa Kategori' }}</span></td>
                    <td class="text-success fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge @if($product->stock > 10) bg-success @else bg-danger @endif rounded-pill">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus produk ini?')"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- arrow paginate -->
         <div class="align-right">
            {{ $products->links() }}
         </div>

    </div>
</div>
@endsection