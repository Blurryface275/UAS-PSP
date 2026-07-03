@extends('layouts.admin')

@section('title', 'Purchase Order')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mt-4">Purchase Order</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    Purchase Order
                </li>
            </ol>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card mb-4 shadow-sm border-0">

                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

                    <span>
                        <i class="fas fa-shopping-cart me-1"></i>
                        Data Purchase Order
                    </span>

                    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Purchase Order
                    </a>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-dark">

                                <tr>

                                    <th width="5%" class="text-center">No</th>

                                    <th>Supplier</th>

                                    <th>Pegawai</th>

                                    <th>Status</th>

                                    <th>Total</th>

                                    <th width="30%" class="text-center">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($purchaseOrders as $po)
                                    <tr>

                                        <td class="text-center">

                                            {{ $loop->iteration }}

                                        </td>

                                        <td>

                                            {{ $po->supplier->name }}

                                        </td>

                                        <td>

                                            {{ $po->user->name }}

                                        </td>

                                        <td>

                                            @switch($po->status)
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
                                                    <span class="badge bg-secondary">{{ ucfirst($po->status) }}</span>
                                            @endswitch

                                        </td>

                                        <td>

                                            Rp {{ number_format($po->estimated_total, 0, ',', '.') }}

                                        </td>

                                        <td class="text-center">

                                            <a href="{{ route('purchase-orders.show', $po->id) }}"
                                                class="btn btn-info btn-sm">

                                                <i class="fas fa-eye"></i>

                                                Detail

                                            </a>

                                            <a href="{{ route('purchase-orders.edit', $po->id) }}"
                                                class="btn btn-warning btn-sm text-dark fw-bold">

                                                <i class="fas fa-edit"></i>

                                                Edit

                                            </a>

                                            @if (auth()->user()?->role === 'administrator' && $po->status === 'pending')
                                                <form action="{{ route('purchase-orders.approve', $po->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-success btn-sm text-white fw-bold">
                                                        <i class="fas fa-check"></i> Setujui
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Tambahan Tombol Terima Barang jika PO sudah diapprove dan belum diklaim masuk -->
                                            @if ($po->status === 'approved' && !$po->purchase()->exists())
                                                <a href="{{ route('purchase-orders.receive', $po->id) }}" class="btn btn-primary btn-sm fw-bold">
                                                    <i class="fas fa-box-open"></i> Terima Barang
                                                </a>
                                            @endif

                                            @if (!in_array($po->status, ['completed', 'cancelled']))
                                                <form action="{{ route('purchase-orders.cancel', $po->id) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin membatalkan Purchase Order ini?')">
                                                    @csrf
                                                    <button class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-times"></i> Batal
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('purchase-orders.destroy', $po->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus Purchase Order ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-danger btn-sm">

                                                    <i class="fas fa-trash"></i>

                                                    Hapus

                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6" class="text-center fw-bold text-danger py-4">

                                            Belum ada Purchase Order.

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
