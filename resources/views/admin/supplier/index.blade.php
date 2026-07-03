@extends('layouts.admin')

@section('title', 'Data Supplier')

@section('content')
<h1 class="mt-4">Manajemen Supplier</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Supplier</li>
</ol>

@if(session('success'))
    <div class="alert alert-success fw-bold">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger fw-bold">{{ session('error') }}</div>
@endif

<div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <span><i class="fas fa-truck me-1"></i> Daftar Supplier</span>
        <a href="{{ route('supplier.create') }}" class="btn btn-primary btn-sm fw-bold">
            <i class="fas fa-plus-circle me-1"></i> Tambah Supplier
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Nama Supplier</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                    <th class="text-center">Total PO</th>
                    <th class="text-center" width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="fw-bold">{{ $supplier->name }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td class="text-muted">{{ Str::limit($supplier->address, 50) }}</td>
                    <td class="text-center">
                        <span class="badge bg-info text-dark rounded-pill">
                            {{ $supplier->purchase_orders_count }} PO
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-warning btn-sm text-dark fw-bold">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin hapus supplier {{ $supplier->name }}?')">
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
                    <td colspan="6" class="text-center text-danger fw-bold py-4">Belum ada supplier terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
