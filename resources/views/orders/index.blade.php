@extends('layouts.front')

@section('content')
<div class="container">
    <h2>Riwayat Pesanan Saya</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>Rp {{ number_format($order->total_amount) }}</td>
                <td>{{ $order->status }}</td>
                <td>
                    <a href="{{ route('orders.tracking', $order->id) }}" class="btn btn-sm btn-info">Lacak</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection