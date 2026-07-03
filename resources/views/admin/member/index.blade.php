@extends('layouts.admin')

@section('title', 'Manajemen Akun Pengguna')

@section('content')
<h1 class="mt-4">Manajemen SDM & Karyawan</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Pengguna</li>
</ol>

@if(session('success'))
    <div class="alert alert-success fw-bold">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger fw-bold">{{ session('error') }}</div>
@endif

<div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <span><i class="fas fa-users-cog me-1"></i> Daftar Semua User</span>
        <a href="{{ route('users.create') }}" class="btn btn-dark btn-sm fw-bold">
            <i class="fas fa-user-plus me-1"></i> Tambah Akun Baru
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th class="text-center">Jabatan (Role)</th>
                    <th class="text-center">Status Akun</th>
                    <th class="text-center" width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="fw-bold">
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span class="badge bg-info ms-1">Anda</span>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">
                        @switch($user->role)
                            @case('administrator')
                                <span class="badge bg-danger">Administrator</span>
                                @break
                            @case('pegawai')
                                <span class="badge bg-primary">Pegawai</span>
                                @break
                            @case('customer')
                                <span class="badge bg-secondary">Customer</span>
                                @break
                        @endswitch
                    </td>
                    <td class="text-center">
                        @if($user->isLocked())
                            <span class="badge bg-danger"><i class="fas fa-lock"></i> Terkunci</span>
                        @else
                            <span class="badge bg-success"><i class="fas fa-check"></i> Aktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm text-dark fw-bold">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin hapus akun {{ $user->name }}? Tindakan ini permanen!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-danger fw-bold py-4">Belum ada akun terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
