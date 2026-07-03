@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mt-4">Detail Pesanan {{ $order->invoice_number }}</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.orders.index') }}">Penjualan</a>
                </li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-7">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="fas fa-box me-2"></i> Rincian Produk</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">Produk</th>
                                            <th>Harga</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end pe-3">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->details as $detail)
                                            <tr>
                                                <td class="ps-3">{{ $detail->product?->name ?? 'Unknown' }}</td>
                                                <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                                <td class="text-center">{{ $detail->qty }}</td>
                                                <td class="text-end pe-3">Rp {{ number_format($detail->price * $detail->qty, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light fw-bold">
                                        <tr>
                                            <td colspan="3" class="text-end">TOTAL KESELURUHAN</td>
                                            <td class="text-end text-success pe-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-muted" width="130">Customer</td>
                                    <td class="fw-bold">: {{ $order->user?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email</td>
                                    <td>: {{ $order->user?->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal Booking</td>
                                    <td>: {{ $order->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status Saat Ini</td>
                                    <td>: 
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info text-dark">Diproses (Dikemas)</span>
                                        @elseif($order->status == 'shipped')
                                            <span class="badge bg-primary">Sedang Dikirim</span>
                                        @elseif($order->status == 'delivered')
                                            <span class="badge bg-success">Telah Diterima & Selesai</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($order->tracking_receipt_number)
                                <tr>
                                    <td class="text-muted">No. Resi</td>
                                    <td class="fw-bold font-monospace">: {{ $order->tracking_receipt_number }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- STATE MANAGEMENT ACTIONS -->
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body text-center">
                            
                            @if($order->status == 'pending')
                                <p class="text-muted mb-3"><small>Customer telah melakukan checkout, segera konfirmasi ketersediaan dan mulai pengemasan.</small></p>
                                <form action="{{ route('admin.orders.process', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning w-100 fw-bold shadow-sm">
                                        <i class="fas fa-box-open me-2"></i> Proses Pesanan (Packing)
                                    </button>
                                </form>

                            @elseif($order->status == 'processing')
                                <p class="text-muted mb-3"><small>Pesanan sedang dikemas. Jika sudah diserahkan ke jasa kirim, masukkan nomor resi lalu tekan tombol di bawah.</small></p>
                                <form action="{{ route('admin.orders.ship', $order->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3 text-start">
                                        <label class="fw-bold mb-1">Nomor Resi (Tracking Receipt)</label>
                                        <input type="text" name="tracking_receipt_number" class="form-control" placeholder="Contoh: JNT12345678" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                                        <i class="fas fa-shipping-fast me-2"></i> Kirim Pesanan
                                    </button>
                                </form>

                            @elseif($order->status == 'shipped')
                                <div class="text-primary py-2">
                                    <i class="fas fa-truck fa-2x mb-2"></i>
                                    <p class="mb-0 fw-bold">Pesanan sedang dalam perjalanan.</p>
                                    <small class="text-muted">Menunggu customer mengklik tombol "Pesanan Diterima".</small>
                                </div>

                            @elseif($order->status == 'delivered')
                                <div class="text-success py-2">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <p class="mb-0 fw-bold">Transaksi Sukses!</p>
                                    <small class="text-muted">Berita acara selesai sepenuhnya.</small>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
