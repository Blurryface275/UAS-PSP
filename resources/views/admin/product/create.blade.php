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
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Pemetaan Kategori</label>
                <select name="category_id" class="form-select" required>
                    <option value="" selected disabled>-- Pilih Kategori Utama --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Harga Patokan (Rp)</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Stok Awal</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Unggah Foto Produk (Opsional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="mb-4">
                <label class="fw-bold">Deskripsi Produk</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary fw-bold"><i class="fas fa-paper-plane"></i> Simpan Produk Baru</button>
            <a href="{{ route('product.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection