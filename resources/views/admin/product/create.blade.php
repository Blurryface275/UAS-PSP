@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
<h1 class="mt-4">Registrasi Produk</h1>
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-white"><i class="fas fa-plus me-1"></i> Form Penambahan Etalase</div>
    <div class="card-body">
        
        <!-- Jangan sampai lupa ENCTYPE jika senjatamu adalah Gambar! -->
        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="fw-bold">Nama Produk</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="fw-bold">Pemetaan Kategori</label>
                <select name="category_id" class="form-select" required>
                    <option value="" selected disabled>-- Pilih Kategori Utama --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Harga Patokan (Rp)</label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Stok Awal</label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Unggah Foto Produk (Opsional)</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="fw-bold">Deskripsi Produk</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary fw-bold"><i class="fas fa-paper-plane"></i> Simpan Produk Baru</button>
            <a href="{{ route('product.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection