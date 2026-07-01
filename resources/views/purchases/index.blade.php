@extends('layouts.admin')

@section('title', 'Penerimaan Barang')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mt-4">Penerimaan Barang</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    Purchases
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
                        <i class="fas fa-truck-loading me-2"></i>
                        Riwayat Penerimaan Barang
                    </h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>No PO</th>
                                    <th>Supplier</th>
                                    <th>Penerima</th>
                                    <th>Status PO</th>
                                    <th>Detail Barang</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($purchases as $purchase)

                                    <tr>

                                        <td>{{ $loop->iteration }}</td>

                                        <td>#{{ $purchase->purchase_order_id }}</td>

                                        <td>
                                            {{ $purchase->purchaseOrder->supplier->name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $purchase->user->name ?? '-' }}
                                        </td>

                                        <td>

                                            @switch($purchase->purchaseOrder->status)
                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">
                                                        Pending
                                                    </span>
                                                @break

                                                @case('completed')
                                                    <span class="badge bg-success">
                                                        Completed
                                                    </span>
                                                @break

                                                @case('cancelled')
                                                    <span class="badge bg-danger">
                                                        Cancelled
                                                    </span>
                                                @break

                                                @default
                                                    <span class="badge bg-secondary">
                                                        {{ ucfirst($purchase->purchaseOrder->status) }}
                                                    </span>
                                            @endswitch

                                        </td>

                                        <td>

                                            @if ($purchase->details->count())
                                                <ul class="mb-0 ps-3">

                                                    @foreach ($purchase->details as $detail)
                                                        <li>
                                                            {{ $detail->product->name }}
                                                            ({{ $detail->qty }} pcs)
                                                        </li>
                                                    @endforeach

                                                </ul>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif

                                        </td>

                                        <td>

                                            {{ $purchase->created_at->format('d M Y') }}

                                        </td>

                                        <td>

                                            Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}

                                        </td>

                                        <td>

                                            @if ($purchase->purchaseOrder->status == 'pending')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock"></i>
                                                    Menunggu Persetujuan Admin
                                                </span>
                                            @elseif($purchase->purchaseOrder->status == 'approved')
                                                <span class="badge bg-info text-dark">
                                                    <i class="fas fa-truck-loading"></i>
                                                    Siap Diproses Penerimaan
                                                </span>
                                            @elseif($purchase->purchaseOrder->status == 'completed')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i>
                                                    Barang Sudah Diterima
                                                </span>
                                            @elseif($purchase->purchaseOrder->status == 'cancelled')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle"></i>
                                                    Dibatalkan
                                                </span>
                                            @endif

                                        </td>

                                    </tr>

                                    @empty

                                        <tr>
                                            <td colspan="9" class="text-center text-danger">
                                                Belum ada data penerimaan barang.
                                            </td>
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
