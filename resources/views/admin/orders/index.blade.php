@extends('layouts.admin')

@section('title', 'Manajemen Penjualan')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mt-4">Pesanan Customer</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    Penjualan
                </li>
            </ol>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Daftar Pesanan Masuk
                    </h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Total Tagihan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold">{{ $order->invoice_number }}</td>
                                        <td>{{ $order->user?->name ?? 'User Dihapus' }}</td>
                                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>
                                                    @break
                                                @case('processing')
                                                    <span class="badge bg-info text-dark"><i class="fas fa-box"></i> Diproses</span>
                                                    @break
                                                @case('shipped')
                                                    <span class="badge bg-primary"><i class="fas fa-truck"></i> Dikirim</span>
                                                    @break
                                                @case('delivered')
                                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Selesai</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td class="text-success fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Detail & Proses
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada pesanan dari customer.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
