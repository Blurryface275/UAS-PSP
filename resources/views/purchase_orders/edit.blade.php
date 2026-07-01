@extends('layouts.admin')

@section('title', 'Edit Purchase Order')

@section('content')
    <div class="row">
        <div class="col-10">
            <h1 class="mt-4">Edit Purchase Order</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('purchase-orders.index') }}">Purchase Order</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <span><i class="fas fa-edit me-1"></i>Form Edit Purchase Order</span>
                </div>

                <div class="card-body">
                    <form action="{{ route('purchase-orders.update', $purchaseOrder->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold">Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Detail Barang</h5>
                        </div>

                        <div id="items-wrapper">
                            @foreach ($purchaseOrder->details as $index => $detail)
                                <div class="card mb-3 item-row">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span>Barang {{ $index + 1 }}</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">Produk</label>
                                                <select name="items[{{ $index }}][product_id]" class="form-select">
                                                    <option value="">-- Pilih Produk --</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" {{ old("items.$index.product_id", $detail->product_id) == $product->id ? 'selected' : '' }}>
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label fw-bold">Qty</label>
                                                <input type="number" name="items[{{ $index }}][qty]" class="form-control" value="{{ old("items.$index.qty", $detail->qty) }}" min="1">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">Harga Satuan</label>
                                                <input type="number" name="items[{{ $index }}][expected_price_per_unit]" class="form-control" value="{{ old("items.$index.expected_price_per_unit", $detail->expected_price_per_unit) }}" min="0" readonly>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">Subtotal</label>
                                                <input type="text" class="form-control subtotal-input" value="Rp {{ number_format($detail->qty * $detail->expected_price_per_unit, 0, ',', '.') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Update Purchase Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.item-row').forEach(function (row) {
                const qtyInput = row.querySelector('input[name$="[qty]"]');
                const priceInput = row.querySelector('input[name$="[expected_price_per_unit]"]');
                const subtotalInput = row.querySelector('.subtotal-input');

                function formatRupiah(value) {
                    return 'Rp ' + Number(value).toLocaleString('id-ID');
                }

                function updateSubtotal() {
                    const qty = parseInt(qtyInput.value || 0);
                    const price = parseInt(priceInput.value || 0);
                    subtotalInput.value = formatRupiah(qty * price);
                }

                qtyInput.addEventListener('input', updateSubtotal);
                updateSubtotal();
            });
        });
    </script>
@endpush
