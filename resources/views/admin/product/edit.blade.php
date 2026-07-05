@extends('layouts.admin')

@section('title', 'Perbarui Informasi Produk')

@section('content')
<h1 class="mt-4">Edit Etalase</h1>
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-white"><i class="fas fa-edit me-1"></i> Form Update Produk</div>
    <div class="card-body">
        
    <!-- wajib ada @csrf dan @method('PUT') untuk update data -->
        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="fw-bold">Nama Produk</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="fw-bold">Pilih Kategori</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $cat)
                    <!-- Ambil kategori lama -->
                    <option value="{{ $cat->id }}" {{ ($product->categories->first()->id ?? 0) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Harga Patokan (Rp)</label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Sisa Stok Fisik</label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Foto Produk (Format: JPG/PNG/WEBP)</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            
                @if($product->image_url)
                    <div class="mt-2 p-2 border rounded d-inline-block bg-light">
                        <img src="{{ asset('storage/'.$product->image_url) }}" height="60" class="rounded object-fit-cover">
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label class="fw-bold">Deskripsi Produk</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-warning fw-bold text-dark"><i class="fas fa-save"></i> Update Product</button>
            <a href="{{ route('product.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection