@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
<div class="row">
    <div class="col-8">
        <h1 class="mt-4">Tambah Kategori</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Master Kategori</a></li>
            <li class="breadcrumb-item active">Tambah Baru</li>
        </ol>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <span><i class="fas fa-plus-circle me-1"></i> Form Kategori Baru</span>
            </div>
            
            <div class="card-body">
                <!-- Arahkan Form POST ini wajib ke route store! -->
                <form action="{{ route('category.store') }}" method="POST">
                    <!-- Penanda Mutlak Framework Laravel: Keamanan CSRF Token -->
                    @csrf 
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Elektronik" required>
                        <!-- Pesan Kesalahan Muncul Jika Lupa Diisi -->
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Masukkan deskripsi..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('category.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection