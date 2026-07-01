@extends('layouts.front')

@section('content')
<div class="container">
    <h3>Detail Pesanan #{{ $order->id }}</h3>
    <p>Status: <strong>{{ strtoupper($order->status) }}</strong></p>
    
    @if($order->tracking_receipt_number)
        <div class="alert alert-success">
            Nomor Resi: {{ $order->tracking_receipt_number }}
        </div>
    @endif

    <hr>
    <h5>Daftar Barang:</h5>
    <ul>
        @foreach($order->details as $item)
            <li>{{ $item->product->name }} - {{ $item->qty }} pcs</li>
        @endforeach
    </ul>
</div>
@endsection