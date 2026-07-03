@extends('layouts.admin')

@section('title', 'Edit Data Pengguna')

@section('content')
<h1 class="mt-4">Perbarui Data User</h1>
<div class="card mb-4 border-0 shadow-sm" style="max-width: 700px;">
    <div class="card-header bg-dark text-white"><i class="fas fa-user-edit me-1"></i> Form Ubah Data User</div>
    <div class="card-body">

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="fw-bold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="fw-bold">Email (Username Login)</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="fw-bold text-danger">Jabatan (Role) <i class="fas fa-exclamation-triangle"></i></label>
                <select name="role" class="form-select border-danger" required>
                    <option value="pegawai"       {{ $user->role === 'pegawai'       ? 'selected' : '' }}>Staff / Pegawai</option>
                    <option value="administrator" {{ $user->role === 'administrator' ? 'selected' : '' }}>Administrator</option>
                    <option value="customer"      {{ $user->role === 'customer'      ? 'selected' : '' }}>Customer</option>
                </select>
                @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <hr class="my-3">
            <p class="text-muted small mb-1"><i class="fas fa-info-circle"></i> Kosongkan kolom password jika tidak ingin mengganti sandi lama.</p>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter...">
                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang...">
                </div>
            </div>

            <button type="submit" class="btn btn-warning fw-bold text-dark px-4">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
        </form>

    </div>
</div>
@endsection
