@extends('layouts.admin')

@section('title', 'Tambah Purchase Order')

@section('content')
    <div class="row">
        <div class="col-10">

            <h1 class="mt-4">Tambah Purchase Order</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('purchase-orders.index') }}">
                        Purchase Order
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Tambah Baru
                </li>
            </ol>

            <div class="card mb-4 shadow-sm border-0">

                <div class="card-header bg-white py-3">

                    <span>

                        <i class="fas fa-plus-circle me-1"></i>

                        Form Purchase Order

                    </span>

                </div>

                <div class="card-body">

                    <form action="{{ route('purchase-orders.store') }}" method="POST">

                        @csrf

                        {{-- Supplier --}}
                        <div class="mb-4">

                            <label class="form-label fw-bold">

                                Supplier <span class="text-danger">*</span>

                            </label>

                            <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">

                                <option value="">-- Pilih Supplier --</option>

                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>

                                        {{ $supplier->name }}

                                    </option>
                                @endforeach

                            </select>

                            @error('supplier_id')
                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>
                            @enderror

                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Detail Barang</h5>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-item-btn">
                                <i class="fas fa-plus"></i> Tambah Barang
                            </button>
                        </div>

                        <div id="items-wrapper">
                            <div class="card mb-3 item-row">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Barang 1</span>
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-item-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Produk</label>
                                            <select name="items[0][product_id]" class="form-select product-select">
                                                <option value="">-- Pilih Produk --</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-bold">Qty</label>
                                            <input type="number" name="items[0][qty]" class="form-control qty-input" value="1" min="1">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Harga Satuan</label>
                                            <input type="number" name="items[0][expected_price_per_unit]" class="form-control price-input" value="0" min="0" readonly>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Subtotal</label>
                                            <input type="text" class="form-control subtotal-input" value="Rp 0" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">

                                Batal

                            </a>

                            <button type="submit" class="btn btn-success">

                                <i class="fas fa-save"></i>

                                Simpan Purchase Order

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
            const wrapper = document.getElementById('items-wrapper');
            const addButton = document.getElementById('add-item-btn');
            let itemCount = 1;

            function formatRupiah(value) {
                return 'Rp ' + Number(value).toLocaleString('id-ID');
            }

            function updateSubtotal(row) {
                const qty = parseInt(row.querySelector('.qty-input').value || 0);
                const price = parseInt(row.querySelector('.price-input').value || 0);
                const subtotalInput = row.querySelector('.subtotal-input');
                subtotalInput.value = formatRupiah(qty * price);
            }

            function attachEvents(row) {
                const productSelect = row.querySelector('.product-select');
                const qtyInput = row.querySelector('.qty-input');
                const priceInput = row.querySelector('.price-input');
                const removeButton = row.querySelector('.remove-item-btn');

                productSelect.addEventListener('change', function () {
                    const selected = this.options[this.selectedIndex];
                    const price = selected.dataset.price || 0;
                    priceInput.value = price;
                    updateSubtotal(row);
                });

                qtyInput.addEventListener('input', function () {
                    updateSubtotal(row);
                });

                removeButton.addEventListener('click', function () {
                    row.remove();
                    renameItems();
                });
            }

            function renameItems() {
                const rows = wrapper.querySelectorAll('.item-row');
                rows.forEach((row, index) => {
                    const header = row.querySelector('.card-header span');
                    header.textContent = `Barang ${index + 1}`;

                    row.querySelector('.product-select').name = `items[${index}][product_id]`;
                    row.querySelector('.qty-input').name = `items[${index}][qty]`;
                    row.querySelector('.price-input').name = `items[${index}][expected_price_per_unit]`;
                });
            }

            addButton.addEventListener('click', function () {
                itemCount++;
                const row = document.createElement('div');
                row.className = 'card mb-3 item-row';
                row.innerHTML = `
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Barang ${itemCount}</span>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-item-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Produk</label>
                                <select name="items[${itemCount - 1}][product_id]" class="form-select product-select">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Qty</label>
                                <input type="number" name="items[${itemCount - 1}][qty]" class="form-control qty-input" value="1" min="1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Harga Satuan</label>
                                <input type="number" name="items[${itemCount - 1}][expected_price_per_unit]" class="form-control price-input" value="0" min="0" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Subtotal</label>
                                <input type="text" class="form-control subtotal-input" value="Rp 0" readonly>
                            </div>
                        </div>
                    </div>
                `;

                wrapper.appendChild(row);
                attachEvents(row);
                updateSubtotal(row);
            });

            document.querySelectorAll('.item-row').forEach(attachEvents);
            document.querySelectorAll('.item-row').forEach(updateSubtotal);
        });
    </script>
@endpush
