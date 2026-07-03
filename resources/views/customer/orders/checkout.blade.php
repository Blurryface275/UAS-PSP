@extends('layouts.front')

@section('title','Checkout')

@section('content')

<h2 class="mb-4">Checkout</h2>

<div class="row">

    <div class="col-md-8">

        <div class="card shadow">

            <div class="card-body">

                <form action="{{ route('customer.orders.checkout') }}" method="POST">

                    @csrf

                    <input type="hidden"
                           name="product_id"
                           value="{{ $product->id }}">

                    <div class="mb-3">

                        <label class="form-label">
                            Produk
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $product->name }}"
                               readonly>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Harga
                        </label>

                        <input type="text"
                               class="form-control"
                               value="Rp {{ number_format($product->price,0,',','.') }}"
                               readonly>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Stock
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $product->stock }}"
                               readonly>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Quantity
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            name="qty"
                            value="1"
                            min="1"
                            max="{{ $product->stock }}"
                            required>

                    </div>

                    <button class="btn btn-success">

                        Checkout

                    </button>

                    <a href="{{ route('customer.products.index') }}"
                       class="btn btn-secondary">

                        Kembali

                    </a>

                </form>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card shadow">

            @if($product->image_url)

                <img src="{{ asset('storage/'.$product->image_url) }}"
                     class="card-img-top">

            @endif

            <div class="card-body">

                <h5>{{ $product->name }}</h5>

                <p>{{ $product->description }}</p>

            </div>

        </div>

    </div>

</div>

@endsection