@extends('layouts.admin')

@section('title', 'Proses Penerimaan')

@section('content')
    <div class="row">
        <div class="col-10">
            <h1 class="mt-4">Proses Penerimaan Barang</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('purchase-orders.index') }}">Purchase Order</a></li>
                <li class="breadcrumb-item active">Penerimaan</li>
            </ol>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <span><i class="fas fa-truck-loading me-1"></i>Form Penerimaan Barang</span>
                </div>
                <div class="card-body">
                    <!-- Combobox drop-down dihapus agar alurnya linier dan langsung mengunci PO dari route -->

                    @if ($purchaseOrder)
                        <form action="{{ route('purchase-orders.receive.store', $purchaseOrder->id) }}" method="POST">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="alert alert-info">
                                Anda sedang menerima barang berdasarkan PO #{{ $purchaseOrder->id }} dari {{ $purchaseOrder->supplier->name ?? '-' }}.
                            </div>

                            <div id="items-wrapper">
                                @foreach ($purchaseOrder->details as $index => $detail)
                                    <div class="card mb-3">
                                        <div class="card-header">{{ $detail->product->name ?? '-' }}</div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Produk</label>
                                                    <input type="text" class="form-control" value="{{ $detail->product->name ?? '-' }}" readonly>
                                                    <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $detail->product_id }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bold">Qty Order</label>
                                                    <input type="number" class="form-control" value="{{ $detail->qty }}" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label fw-bold">Qty Diterima</label>
                                                    <input type="number" name="items[{{ $index }}][qty_received]" class="form-control" value="{{ $detail->qty }}" min="1">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bold">Harga Aktual</label>
                                                    <input type="number" name="items[{{ $index }}][actual_price_per_unit]" class="form-control" value="{{ $detail->expected_price_per_unit }}" min="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan Penerimaan
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-secondary">
                            Silakan pilih Purchase Order terlebih dahulu untuk memulai proses penerimaan barang.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
