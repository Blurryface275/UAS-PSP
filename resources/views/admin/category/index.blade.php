@extends('layouts.admin')

@section('title', 'Master Kategori')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mt-4">Master Kategori</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Master Kategori</li>
        </ol>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table me-1"></i> Data Kategori Toko</span>
                <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Baru
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="25%">Nama Kategori</th>
                                <th width="45%">Deskripsi</th>
                                <th width="25%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Looping Data dari CategoryController@index -->
                            @forelse($categories as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="fw-bold">{{ $item->name }}</td>
                                <td class="text-muted">{{ Str::limit($item->description, 60) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('category.edit', $item->id) }}" class="btn btn-warning btn-sm text-dark fw-bold">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    
                                    <!-- Aksi Hapus Wajib Pakai Method Form POST + DELETE demi Keamanan (Bukan link href Biasa!) -->
                                    <form action="{{ route('category.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus
                                     kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center fw-bold text-danger py-4">Belum ada satupun Kategori yang masuk di Database.</td>
                            </tr>
                            @endempty
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection