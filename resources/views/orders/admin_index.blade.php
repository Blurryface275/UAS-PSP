@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Panel Pesanan Pegawai</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>Rp {{ number_format($order->total_amount) }}</td>
                        <td>
                            <span class="badge badge-primary">{{ strtoupper($order->status) }}</span>
                        </td>
                        <td>
                            @if($order->status == 'pending')
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="processing">
                                    <button type="submit" class="btn btn-sm btn-warning">Proses Pesanan</button>
                                </form>
                            @elseif($order->status == 'processing')
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="text" name="tracking_receipt_number" placeholder="Masukkan Resi" required class="form-control-sm">
                                    <input type="hidden" name="status" value="shipped">
                                    <button type="submit" class="btn btn-sm btn-success">Kirim (Input Resi)</button>
                                </form>
                            @else
                                <span>{{ $order->tracking_receipt_number ?? 'Selesai' }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection