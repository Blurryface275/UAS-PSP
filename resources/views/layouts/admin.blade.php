<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>@yield('title', 'Admin Dashboard') - SB Admin</title>
    <!-- SB Admin Fonts / CSS / DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .sb-nav-fixed {
            padding-top: 56px;
        }

        .sb-sidenav {
            display: flex;
            flex-direction: column;
            height: 100%;
            flex-wrap: nowrap;
        }

        #layoutSidenav {
            display: flex;
        }

        #layoutSidenav_nav {
            flex-basis: 225px;
            flex-shrink: 0;
            transition: transform 0.15s ease-in-out;
            z-index: 1038;
            transform: translateX(-225px);
        }

        #layoutSidenav_content {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            flex-grow: 1;
            min-height: calc(100vh - 56px);
            margin-left: -225px;
            transition: margin 0.15s ease-in-out;
        }

        .sb-sidenav-toggled #layoutSidenav_nav {
            transform: translateX(0);
        }

        .sb-sidenav-toggled #layoutSidenav_content {
            margin-left: 0;
        }

        @media (min-width: 992px) {
            #layoutSidenav_nav {
                transform: translateX(0);
            }

            #layoutSidenav_content {
                margin-left: 0;
            }

            .sb-sidenav-toggled #layoutSidenav_nav {
                transform: translateX(-225px);
            }

            .sb-sidenav-toggled #layoutSidenav_content {
                margin-left: -225px;
            }
        }

        .sb-sidenav-dark {
            background-color: #212529;
            color: rgba(255, 255, 255, 0.5);
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link {
            color: rgba(255, 255, 255, 0.5);
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover {
            color: #fff;
        }

        .sb-sidenav-dark .sb-sidenav-menu .sb-sidenav-menu-heading {
            padding: 1.75rem 1rem 0.75rem;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
    @stack('styles')
</head>

<body class="sb-nav-fixed">
    <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
        <!-- Sidebar Toggle (Kiri) -->
        <button class="btn btn-link btn-sm ms-3 text-white-50" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Brand (Kanan) -->
        <a class="navbar-brand ms-2 text-truncate" style="width: 175px;" href="#">Sistem Admin</a>
        <!-- Navbar-->
        <ul
            class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i> {{ auth()->user()->name ?? 'Guest' }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav flex-column">

                        <!-- AREA UTAMA -->
                        <div class="sb-sidenav-menu-heading">Utama</div>
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <!-- AREA MODUL 1: MASTER DATA (Steve) -->
                        <div class="sb-sidenav-menu-heading">Modul 1: Master Data</div>
                        <a class="nav-link" href="{{ route('category.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                            Data Kategori
                        </a>
                        <a class="nav-link" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-box-open"></i></div>
                            Data Barang / Produk
                        </a>
                        <a class="nav-link" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                            Manajemen User
                        </a>

                        <!-- AREA MODUL 2: PENGADAAN (Inbound) -->
                        <div class="sb-sidenav-menu-heading">Modul 2: Suplai Area</div>
                        <a class="nav-link" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-truck"></i></div>
                            Data Supplier
                        </a>
                        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#purchaseSubmenu" aria-expanded="false" aria-controls="purchaseSubmenu">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            Purchase Order (PO)
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="purchaseSubmenu">
                            <nav class="sb-sidenav-menu-nested nav flex-column ms-3">
                                <a class="nav-link py-2" href="#"><i class="fas fa-list me-2"></i> Buat PO Baru</a>
                                <a class="nav-link py-2" href="#"><i class="fas fa-box-open me-2"></i> Terima Barang</a>
                            </nav>
                        </div>

                        <!-- AREA MODUL 3: PENJUALAN (Outbound - Kenny) -->
                        <div class="sb-sidenav-menu-heading">Modul 3: Sales Area</div>
                        <a class="nav-link" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                            Pesanan Customer
                        </a>
                        <a class="nav-link" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-shipping-fast"></i></div>
                            Update Pengiriman
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer p-3 position-absolute bottom-0"
                    style="background-color: #343a40; color: #fff;">
                    <div class="small">Logged in as:</div>
                    Start Bootstrap Admin
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mt-4">
                    @yield('content')
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto border-top">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2026</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- Core SB Admin UI Logic -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const sidebarToggle = document.body.querySelector('#sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', event => {
                    event.preventDefault();
                    document.body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains(
                        'sb-sidenav-toggled'));
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
