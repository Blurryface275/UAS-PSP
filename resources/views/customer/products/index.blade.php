@extends('layouts.front')

@section('title','Shop')

@section('content')

<h2 class="mb-4">Shop</h2>

<div class="row">

@forelse($products as $product)

<div class="col-md-3 mb-4">

    <div class="card h-100 shadow-sm">

        @if($product->image_url)

            <img src="{{ asset('storage/'.$product->image_url) }}"
                 class="card-img-top"
                 style="height:220px;object-fit:cover;">

        @else

            <img src="https://placehold.co/600x600?text=No+Image"
                 class="card-img-top"
                 style="height:220px;object-fit:cover;">

        @endif

        <div class="card-body">

            <h5>{{ $product->name }}</h5>

            <p class="text-muted">

                Rp {{ number_format($product->price,0,',','.') }}

            </p>

            <p>

                Stock :
                <strong>{{ $product->stock }}</strong>

            </p>

        </div>

        <div class="card-footer bg-white">

            <a href="{{ route('customer.orders.checkout.show',['id'=>$product->id]) }}"
               class="btn btn-primary w-100">

                Beli

            </a>

        </div>

    </div>

</div>

@empty

<div class="col-12">

    <div class="alert alert-info">

        Belum ada produk.

    </div>

</div>

@endforelse

</div>

<div class="mt-3">

{{ $products->links() }}

</div>

@endsection