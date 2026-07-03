@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mt-4">Laporan Penjualan & Revenue</h1>
        
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Laporan Penjualan</li>
        </ol>

        <!-- Filter Form -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <i class="fas fa-filter me-1"></i> Filter Laporan
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                    </div>

                    <div class="col-md-4 mt-4 pt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Terapkan Filter
                        </button>
                        
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Rekapitulasi Data -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-table me-1"></i> Rekapitulasi Penjualan (Terkirim)
                </span>
                <a href="{{ route('admin.reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                   class="btn btn-danger btn-sm" target="_blank">
                    <i class="fas fa-file-pdf me-1"></i> Cetak Laporan PDF
                </a>
            </div>
            
            <div class="card-body">
                
                @if($sales->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Invoice</th>
                                    <th>Tgl Transaksi</th>
                                    <th>Customer</th>
                                    <th>Item Produk & Qty</th>
                                    <th>Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $index => $sale)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $sale->invoice_number }}</td>
                                    <td>{{ $sale->created_at->format('d M Y - H:i') }}</td>
                                    <td>{{ $sale->user->name ?? '-' }}</td>
                                    <td>
                                        <ul class="mb-0 ps-3">
                                            @foreach($sale->details as $detail)
                                                <li>{{ $detail->product->name ?? 'Produk Dihapus' }} (x{{ $detail->qty }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="5" class="text-end fs-5">TOTAL REVENUE (KEUNTUNGAN KOTOR) :</th>
                                    <th class="text-end fs-5 text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning text-center my-4">
                        <i class="fas fa-info-circle fa-2x mb-3 d-block"></i>
                        Tidak ada transaksi penjualan sukses atau terkirim (delivered) pada rentang waktu tanggal ini.
                    </div>
                @endif
                
            </div>
        </div>
        
    </div>
</div>
@endsection
