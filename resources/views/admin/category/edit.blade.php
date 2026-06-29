@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="row">
    <div class="col-8">
        <h1 class="mt-4">Edit Kategori</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Master Kategori</a></li>
            <li class="breadcrumb-item active">Ubah Data</li>
        </ol>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <span><i class="fas fa-edit me-1"></i> Form Edit Kategori</span>
            </div>
            
            <div class="card-body">
                <!-- Arahkan Form POST ini ke route update, Kuncinya: WAJIB injeksi ID kategorinya! -->
                <form action="{{ route('category.update', $category->id) }}" method="POST">
                    @csrf 
                    
                    <!-- HTML di browser tidak mengenal metode PUT (modifikasi), 
                         Biar tembus, kita selipkan peretas perintah bawaan Laravel ini: -->
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <!-- Titik kunci: Sisipkan $category->name sebagai nilai lama di dalam fungsi old() -->
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('category.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning fw-bold text-dark"><i class="fas fa-save"></i> Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection