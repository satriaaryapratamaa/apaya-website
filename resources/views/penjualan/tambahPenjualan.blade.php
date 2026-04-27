@extends('adminlte::page')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary rounded-3 p-2 me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-cart-plus" viewBox="0 0 16 16">
                            <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9V5.5z"/>
                            <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                    </div>
                    <h2 class="h4 mb-0 fw-bold">Tambah Penjualan Baru</h2>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger rounded-3 border-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success rounded-3 border-0">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif
                        <form action="{{route('penjualan.store')}}" method="post">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-secondary">Tanggal Laporan Harian</label>
                                    <input type="date" name="tanggal_penjualan" class="form-control bg-light border-0 shadow-none" value="{{date('Y-m-d')}}" required>
                                </div>
                                <div class="col-12 mt-4">
                                    <label class="form-label small fw-semibold text-secondary">Cek & Laporan Sisa Stok Fisik Produk</label>
                                    <table class="table table-bordered text-center" id="productTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th style="width: 40%">Produk</th>
                                                <th style="width: 25%">Stok Sistem Saat Ini</th>
                                                <th style="width: 25%">Stok Fisik di Rak</th>
                                                <th style="width: 10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="addProduct">
                                        <i class="bi bi-plus-circle me-1"></i>Tambah Produk ke Laporan
                                    </button>
                                </div>

                                <div class="col-12 mt-5">
                                    <button type="submit" class="btn btn-primary w-100">Hitung Sistem Delta & Simpan</button>
                                    <a href="{{route('penjualan.index')}}" class="btn btn-link w-100 text-decoration-none text-muted mt-2">Batalkan</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let rowIdx = 0;
        document.getElementById('addProduct').addEventListener('click', function() {
            rowIdx++;
            const tbody = document.querySelector('#productTable tbody');
            const newRow =`
                <tr id="row${rowIdx}">
                    <td>
                        <select name="items[${rowIdx}][produk_id]" class="form-select select-produk" required>
                            <option value="">---Pilih Produk---</option>
                            @foreach($produks as $p)
                                <option value="{{$p->id}}" data-stok="{{$p->stok_saat_ini}}">{{$p->nama_produk}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control stok-server text-center" readonly placeholder="Otomatis">
                    </td>
                    <td>
                        <input type="number" name="items[${rowIdx}][stok_saat_ini_fisik]" class="form-control text-center" min="0" placeholder="Masukkan 0 jika habis..." required>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
            tbody.insertAdjacentHTML('beforeend', newRow);
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('select-produk')) {
                const stokVal = e.target.options[e.target.selectedIndex].dataset.stok;
                const row = e.target.closest('tr');
                row.querySelector('.stok-server').value = stokVal ? stokVal : '';
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove') || e.target.closest('.remove')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
@endsection
