@extends('layouts.admin')

@section('title', 'Edit Supplier')

@section('content')
<h1 class="mt-4">Perbarui Data Supplier</h1>
<div class="card mb-4 border-0 shadow-sm" style="max-width: 650px;">
    <div class="card-header bg-white"><i class="fas fa-edit me-1 text-warning"></i> Form Edit Supplier</div>
    <div class="card-body">

        <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="fw-bold">Nama Perusahaan / Supplier <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $supplier->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="fw-bold">Nomor Telepon <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone', $supplier->phone) }}" required>
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="fw-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                          required>{{ old('address', $supplier->address) }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-warning fw-bold text-dark">
                <i class="fas fa-save me-1"></i> Update Supplier
            </button>
            <a href="{{ route('supplier.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
        </form>

    </div>
</div>
@endsection
