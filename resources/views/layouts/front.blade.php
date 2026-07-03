<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>@yield('title', 'Toko Online')</title>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @stack('styles')
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">

            <a class="navbar-brand fw-bold" href="{{ route('customer.products.index') }}">
                <i class="bi bi-shop"></i> Toko Online
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- Menu -->
                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('customer.dashboard') }}">
                            <i class="bi bi-house"></i>
                            Beranda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('customer.products.index') }}">
                            <i class="bi bi-box"></i>
                            Produk
                        </a>
                    </li>

                    @auth
                        @if(auth()->user()->role == 'customer')

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('customer.orders.index') }}">
                                <i class="bi bi-bag-check"></i>
                                Pesanan Saya
                            </a>
                        </li>

                        @endif
                    @endauth

                </ul>

                <!-- Right Menu -->
                <div class="d-flex align-items-center">

                    <!-- Cart (sementara disabled) -->
                    <button class="btn btn-outline-secondary me-3" disabled>
                        <i class="bi bi-cart-fill"></i>
                        Cart
                    </button>

                    @auth

                        <div class="dropdown">

                            <a class="btn btn-outline-dark dropdown-toggle"
                               href="#"
                               data-bs-toggle="dropdown">

                                <i class="bi bi-person-circle"></i>
                                {{ auth()->user()->name }}

                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">

                                @if(auth()->user()->role == 'administrator' || auth()->user()->role == 'pegawai')

                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('dashboard') }}">
                                            <i class="bi bi-speedometer2"></i>
                                            Dashboard Admin
                                        </a>
                                    </li>

                                @endif

                                @if(auth()->user()->role == 'customer')

                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('customer.profile.edit') }}">
                                            <i class="bi bi-person"></i>
                                            Profile
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('customer.orders.index') }}">
                                            <i class="bi bi-bag-check"></i>
                                            Pesanan Saya
                                        </a>
                                    </li>

                                @endif

                                <li><hr class="dropdown-divider"></li>

                                <li>

                                    <a class="dropdown-item text-danger"
                                       href="#"
                                       onclick="event.preventDefault();document.getElementById('logout-form-front').submit();">

                                        <i class="bi bi-box-arrow-right"></i>
                                        Logout

                                    </a>

                                    <form id="logout-form-front"
                                          action="{{ route('logout') }}"
                                          method="POST"
                                          class="d-none">

                                        @csrf

                                    </form>

                                </li>

                            </ul>

                        </div>

                    @else

                        <a href="{{ route('login') }}"
                           class="btn btn-primary">

                            Login

                        </a>

                    @endauth

                </div>

            </div>

        </div>
    </nav>

    <!-- Header -->
    <header class="bg-dark text-white py-5">
        <div class="container text-center">

            <h1 class="display-5 fw-bold">
                Selamat Datang di Toko Online
            </h1>

            <p class="lead">
                Temukan berbagai produk berkualitas dengan harga terbaik.
            </p>

        </div>
    </header>

    <!-- Content -->
    <section class="py-5">
        <div class="container">

            @yield('content')

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-3 mt-5">
        <div class="container text-center">

            <small>
                Copyright © {{ date('Y') }}
                Toko Online | OLL Modul 3
            </small>

        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>