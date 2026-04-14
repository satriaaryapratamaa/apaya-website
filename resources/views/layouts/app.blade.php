<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Dashboard UMKM') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            margin: 0;
        }

        .wrapper {
            display: flex;
            overflow-y: auto;
            align-items: stretch;
            min-height: 100vh;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background-color: #3f51b5 !important;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            z-index: 1040;
        }

        #sidebar::-webkit-scrollbar {
            width: 10px;
        }

        #sidebar:::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #303f9f;
            text-align: center;
        }

        #sidebar ul.components {
            padding: 10px 0;
            margin: 0;
            list-style: none !important;
        }

        #sidebar ul li {
            list-style-type: none !important;
        }

        #sidebar ul li a {
            padding: 15px 20px;
            font-size: 1rem;
            display: block;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none !important;
            transition: 0.3s;
        }

        #sidebar ul li a:hover,
        #sidebar ul li a.active {
            color: #fff;
            background-color: #283593;
            border-left: 4px solid #ff4081;
        }

        #sidebar ul li a i {
            margin-right: 10px;
            /* padding-top: 10px; */
            width: 15px;
            text-align: center;
        }

        #content {
            margin-left: 250px;
            margin-top: 50px;
            width: calc(100% - 250px);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: all 0.3s;
        }

        #sidebar.active ~ #content {
            margin-left: 0;
            width: 100%;
        }

        .navbar-custom {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 15px 20px;
            position: fixed;
            top: 0;
            right: 0;
            width: calc(100% - 250px);
            z-index: 1030;
            transition: all 0.3s;
            /* width: 100%; */
        }
        #sidebar.active ~ #content .navbar-custom {
            width: 100%;
        }

        .arrow {
            transition: transform 0.3s ease;
        }

        [aria-expanded="true"] .arrow {
            transform: rotate(180deg);
        }
    </style>
</head>
<body>

<div class="wrapper">

    <nav id="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0 fw-bold">UMKM</h4>
        </div>

        <ul class="components list-unstyled">
            <li>
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href='#submenuPenjualan' data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center {{ request()->is('penjualan.*') ? 'active' : 'collapse' }}" aria-expanded="{{request()->routeIs('penjualan.*') ? 'true' : 'false'}}">
                    <span><i class="bi bi-cart2"></i>Penjualan</span><i class="bi bi-chevron-down arrow"></i>
                </a>
                <div class="collapse {{request()->routeIs('penjualan.*') ? 'show' : ''}}" id="submenuPenjualan">
                    <ul>
                        <li>
                            <a href="{{route('penjualan.create')}}" class="{{ request()->routeIs('penjualan.create') ? 'active' : '' }}">
                                <i class="fas fa-desktop"></i> Tambah Penjualan
                            </a>
                        </li>
                        <li>
                            <a href="{{route('penjualan.index')}}" class="{{ request()->routeIs('penjualan.index') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice-dollar"></i> Riwayat Penjualan
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href='{{ route('retur.index') }}' class="{{ request()->routeIs('retur.*') ? 'active' : '' }}">
                    <i class="fas fa-undo-alt"></i> Retur Penjualan
                </a>
            </li>
            <li>
                <a href='#' class="">
                    <i class="bi bi-truck"></i> Pembelian
                </a>
            </li>
            <li>
                <a href='#' class="">
                    <i class="bi bi-box-seam"></i> Retur Pembelian
                </a>
            </li>
            <li>
                <a href='#' class="">
                    <i class="bi bi-wallet"></i> Pencatatan Biaya
                </a>
            </li>
            <hr>
            <div class="fs-10 ms-3">Master Data</div>
            <li>
                <a href="#" class="{{ request()->is('produk*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Pegawai
                </a>
            </li>
            <li>
                <a href="#" class="{{ request()->is('produk*') ? 'active' : '' }}">
                    <i class="bi bi-person"></i> Pengguna
                </a>
            </li>
            <li>
                <a href="#" class="{{ request()->is('produk*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Barang
                </a>
            </li>
            <li>
                <a href="#" class="{{ request()->is('produk*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Supplier
                </a>
            </li>
            <li>
                <a href="#" class="{{ request()->is('produk*') ? 'active' : '' }}">
                    <i class="bi bi-wallet"></i> Akun Biaya
                </a>
            </li>
            <hr>
            <div class="fs-10 ms-3">Laporan</div>
            <li>
                <a href='{{ route('laporan.penjualan') }}' class="{{ request()->routeIs('laporan.penjualan') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-medical"></i> Lap.Penjualan
                </a>
            </li>
            <li>
                <a href='#' class="">
                    <i class="bi bi-file-earmark-medical"></i> Lap.Pembelian
                </a>
            </li>
            <li>
                <a href='{{ route('laporan.retur') }}' class="{{ request()->routeIs('laporan.retur') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-medical"></i> Lap.ReturPenjualan
                </a>
            </li>
            <li>
                <a href='#' class="">
                    <i class="bi bi-file-earmark-medical"></i> Lap.ReturPembelian
                </a>
            </li>
            <li>
                <a href='#' class="">
                    <i class="bi bi-file-earmark-medical"></i> Lap.StokBarang
                </a>
            </li>
            <li>
                <a href='#' class="">
                    <i class="bi bi-file-earmark-medical"></i> Lap.RugiLaba
                </a>
            </li>
            <hr>
            <div class="fs-10 ms-3">Pengaturan</div>
            <li>
                <a href='#' class="">
                    <i class="bi bi-shield"></i> Hak Akses
                </a>
            </li>
        </ul>
    </nav>

    <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary border-0">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>

                    <div class="ms-auto d-flex align-items-center">
                        <a href="#" class="text-secondary position-relative me-4">
                            <i class="fas fa-bell fa-lg"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;"></span>
                        </a>

                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle fa-2x text-primary me-2"></i>
                                <strong>Admin</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#">Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="p-4 flex-grow-1">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>

</body>
</html>
