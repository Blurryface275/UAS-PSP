@extends('layouts.admin')

@section('title', 'Detail Purchase Order')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mt-4">Detail Purchase Order</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('purchase-orders.index') }}">Purchase Order</a>
                </li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-info-circle me-1"></i>Informasi Purchase Order</span>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>No PO:</strong> #{{ $purchaseOrder->id }}</p>
                            <p class="mb-2"><strong>Status:</strong>
                                @switch($purchaseOrder->status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-info text-dark">Approved</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Completed</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($purchaseOrder->status) }}</span>
                                @endswitch
                            </p>
                            <p class="mb-2"><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Pegawai:</strong> {{ $purchaseOrder->user->name ?? '-' }}</p>
                            <p class="mb-2"><strong>Total Estimasi:</strong> Rp {{ number_format($purchaseOrder->estimated_total, 0, ',', '.') }}</p>
                            <p class="mb-2"><strong>Dibuat:</strong> {{ $purchaseOrder->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <h5 class="mb-3">Daftar Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrder->details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->product->name ?? '-' }}</td>
                                        <td>{{ $detail->qty }}</td>
                                        <td>Rp {{ number_format($detail->expected_price_per_unit, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($detail->qty * $detail->expected_price_per_unit, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <div class="d-flex gap-2">
                            @if (auth()->user()?->role === 'administrator' && $purchaseOrder->status === 'pending')
                                <form action="{{ route('purchase-orders.approve', $purchaseOrder->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-check me-1"></i> Setujui PO
                                    </button>
                                </form>
                            @endif

                            @if (!in_array($purchaseOrder->status, ['completed', 'cancelled']))
                                <form action="{{ route('purchase-orders.cancel', $purchaseOrder->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-times me-1"></i> Batalkan PO
                                    </button>
                                </form>
                            @endif

                            @if ($purchaseOrder->status === 'approved' && !$purchaseOrder->purchase()->exists())
                                <a href="{{ route('purchase-orders.receive', $purchaseOrder->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-truck-loading me-1"></i> Proses Penerimaan
                                </a>
                            @elseif ($purchaseOrder->status === 'completed')
                                <span class="btn btn-outline-success btn-sm disabled">PO Selesai</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
