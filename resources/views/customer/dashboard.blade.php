@extends('layouts.front')

@section('title', 'Customer Dashboard')

@section('content')

<div class="container">

    <div class="mb-4">
        <h2 class="fw-bold">
            Selamat Datang, {{ auth()->user()->name }} 👋
        </h2>
        <p class="text-muted">
            Kelola akun dan pantau pesanan Anda di sini.
        </p>
    </div>

    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-bag-fill fs-1 text-primary"></i>
                    <h3 class="mt-2">{{ $totalOrders }}</h3>
                    <p class="mb-0">Total Pesanan</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-truck fs-1 text-warning"></i>
                    <h3 class="mt-2">{{ $activeOrders }}</h3>
                    <p class="mb-0">Pesanan Aktif</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                    <h3 class="mt-2">{{ $completedOrders }}</h3>
                    <p class="mb-0">Pesanan Selesai</p>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-primary text-white">
                    Profil Saya
                </div>

                <div class="card-body text-center">

                    @if(auth()->user()->profile_picture)

                        <img src="{{ asset('storage/'.auth()->user()->profile_picture) }}"
                             class="rounded-circle mb-3"
                             width="120"
                             height="120"
                             style="object-fit:cover;">

                    @else

                        <i class="bi bi-person-circle"
                           style="font-size:100px"></i>

                    @endif

                    <h5>{{ auth()->user()->name }}</h5>

                    <p class="text-muted">
                        {{ auth()->user()->email }}
                    </p>

                    <a href="{{ route('customer.profile.edit') }}"
                       class="btn btn-primary w-100">

                        Edit Profil

                    </a>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-success text-white">
                    Menu Cepat
                </div>

                <div class="list-group list-group-flush">

                    <a href="{{ route('customer.products.index') }}"
                       class="list-group-item list-group-item-action">

                        🛍 Belanja Sekarang

                    </a>

                    <a href="{{ route('customer.orders.index') }}"
                       class="list-group-item list-group-item-action">

                        📦 Pesanan Saya

                    </a>

                    <a href="{{ route('customer.profile.edit') }}"
                       class="list-group-item list-group-item-action">

                        👤 Edit Profil

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection