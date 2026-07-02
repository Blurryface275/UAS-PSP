@extends('layouts.admin')

@section('title', 'Perbarui Informasi Produk')

@section('content')
<h1 class="mt-4">Edit Etalase</h1>
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-white"><i class="fas fa-edit me-1"></i> Form Berbahaya</div>
    <div class="card-body">
        
        <!-- JANTUNGNYA UPLOAD: Kamu WAJIB menyuntikkan enctype='multipart/form-data'. 
             Jika terlewat 1 huruf saja, gambar hanya terbaca sebagai teks rusak! -->
        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="fw-bold">Nama Kosmetik/Produk</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Pemetaan Kategori</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $cat)
                    <!-- Logika cerdas mencari kategori lamanya agar terpilih otomatis (Selected) -->
                    <option value="{{ $cat->id }}" {{ ($product->categories->first()->id ?? 0) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Harga Patokan (Rp)</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Sisa Stok Fisik</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Manajemen Berkasi Foto (Format: JPG/PNG/WEBP)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <div class="form-text text-danger">* Biarkan kosong bila kamu sepakat tidak merubah foto lawas ini.</div>
                
                <!-- Secuil intipan bila web punya file foto -->
                @if($product->image_url)
                    <div class="mt-2 p-2 border rounded d-inline-block bg-light">
                        <img src="{{ asset('storage/'.$product->image_url) }}" height="60" class="rounded object-fit-cover">
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label class="fw-bold">Catatan / Deskripsi Lapak</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning fw-bold text-dark"><i class="fas fa-save"></i> Update Product</button>
            <a href="{{ route('product.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection