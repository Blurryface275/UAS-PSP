@extends('layouts.front')

@section('title','Tracking Order')

@section('content')

<h2 class="mb-4">Tracking Pesanan</h2>

<div class="card">

    <div class="card-header">

        Invoice #{{ $order->invoice_number }}

    </div>

    <div class="card-body">

        <table class="table">

            <tr>

                <th>Status</th>

                <td>
                    @if($order->status == 'pending')

                        <span class="badge bg-warning">Pending</span>

                    @elseif($order->status == 'processing')

                        <span class="badge bg-info">Processing</span>

                    @elseif($order->status == 'shipped')

                        <span class="badge bg-primary">Shipped</span>

                        <form action="{{ route('customer.orders.received', $order->id) }}"
                            method="POST"
                            class="mt-2">
                            @csrf

                            <button class="btn btn-success btn-sm">
                                Pesanan Sudah Diterima
                            </button>
                        </form>

                    @elseif($order->status == 'delivered')

                        <span class="badge bg-success">Delivered</span>

                    @elseif($order->status == 'cancelled')

                        <span class="badge bg-danger">Cancelled</span>

                    @endif
                </td>

            </tr>

            <tr>

                <th>Total</th>

                <td>

                    Rp {{ number_format($order->total_amount,0,',','.') }}

                </td>

            </tr>

            <tr>

                <th>Nomor Resi</th>

                <td>

                    {{ $order->tracking_receipt_number ?? '-' }}

                </td>

            </tr>

        </table>

    </div>

</div>

@endsection