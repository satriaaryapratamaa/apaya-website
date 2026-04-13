@extends('layouts.app')

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
                        <form action="{{route('penjualan.store')}}" method="post">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="namaPenjualan" class="form-label small fw-semibold text-secondary">Nama Pelanggan</label>
                                    <input type="text" name="namaPenjualan" class="form-control form-control-lg bg-light border-0 shadow-none" id="namaPenjualan" placeholder="Contoh: Budi Santoso" required>
                                </div>

                                <div class="col-12">
                                    <label for="invoice" class="form-label small fw-semibold text-secondary">No. Invoice</label>
                                    <input type="text" name="invoice" class="form-control bg-light border-0" id="invoice" value="INV-20231012-001" readonly>
                                    <div class="form-text">ID Transaksi dibuat otomatis oleh sistem.</div>
                                </div>

                                <div class="col-md-7">
                                    <label for="totalBayar" class="form-label small fw-semibold text-secondary">Total Bayar</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">Rp</span>
                                        <input type="number" name="totalBayar" class="form-control form-control-lg bg-light border-0 shadow-none" id="totalBayar" placeholder="0" required>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <label for="status" class="form-label small fw-semibold text-secondary">Status Pembayaran</label>
                                    <select name="status" id="status" class="form-select form-select-lg bg-light border-0 shadow-none">
                                        <option value="lunas" selected>Lunas</option>
                                        <option value="hutang">Hutang</option>
                                        <option value="dibatalkan">Dibatalkan</option>
                                    </select>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                                    <a href="{{route('penjualan.index')}}" class="btn btn-link w-100 text-decoration-none text-muted mt-2">Batalkan</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
