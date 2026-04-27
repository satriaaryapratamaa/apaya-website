@extends('adminlte::page')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="container-fluid p-4">
        <h2 class="mb-4 fw-bold">Riwayat Penjualan</h2>
        <button class="mb-4 btn btn-primary"><a href="{{route('penjualan.create')}}" class="text-white text-decoration-none">Tambah Penjualan</a></button>
        <div class="card border-0 rounded-3 shadow-sm p-4 mb-4">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Tanggal Penjualan</th>
                            <th>Kode Barang (SKU)</th>
                            <th>Nama Produk</th>
                            <th>Satuan</th>
                            <th class="text-danger">Qty Terjual</th>
                            <th>Harga Jual</th>
                            <th>Total Penjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penjualans as $p)
                            <tr>
                                <td class="fw-bold text-secondary">TRX-{{ date('Ymd', strtotime($p->tanggal_penjualan)) }}-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal_penjualan)->format('d M Y') }}</td>
                                <td class="font-monospace text-muted">{{ $p->produk?->sku ?? '-' }}</td>
                                <td class="fw-bold">{{ $p->produk?->nama_produk ?? 'Produk Terhapus' }}</td>
                                <td>Pcs</td>
                                <td class="text-danger fw-bold">-{{ $p->jumlah_terjual }}</td>
                                <td>Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                                <td class="fw-bold">Rp {{ number_format($p->total_omzet, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Belum ada riwayat penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/javascript.bundle.min.js"></script>
@endsection

