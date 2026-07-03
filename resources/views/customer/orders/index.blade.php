@extends('layouts.front')

@section('title', 'My Orders')

@section('content')

<div class="container">

    <h2 class="mb-4">
        My Orders
    </h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->count() == 0)

        <div class="alert alert-info">

            Belum ada pesanan.

        </div>

    @else

    <table class="table table-bordered table-hover">

        <thead class="table-dark">

            <tr>

                <th>Invoice</th>

                <th>Total</th>

                <th>Status</th>

                <th>Action</th>

            </tr>

        </thead>

        <tbody>

        @foreach($orders as $order)

            <tr>

                <td>#{{ $order->id }}</td>

                <td>

                    Rp {{ number_format($order->total_amount,0,',','.') }}

                </td>

                <td>

                    <span class="badge bg-primary">

                        {{ ucfirst($order->status) }}

                    </span>

                </td>

                <td>

                    <a href="{{ route('customer.orders.show',$order->id) }}"
                        class="btn btn-sm btn-primary">

                        Detail

                    </a>

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    @endif

</div>

@endsection