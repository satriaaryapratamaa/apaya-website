@extends('adminlte::page')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="card border-0 shadow-sm p-4 mb-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Laporan Riwayat Penjualan (Stok Keluar)</h4>
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak Laporan</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Qty Terjual</th>
                    <th>Harga Satuan</th>
                    <th>Total Omzet</th>
                    <th>Keuntungan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualans as $idx => $trx)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="fw-bold">TRX-{{ \Carbon\Carbon::parse($trx->tanggal_penjualan)->format('Ymd') }}-{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($trx->tanggal_penjualan)->format('d M Y') }}</td>
                    <td class="fw-bold">{{ $trx->produk->nama_produk ?? 'Barang Terhapus' }}</td>
                    <td>{{ $trx->jumlah_terjual }} Pcs</td>
                    <td>Rp {{ number_format($trx->harga_jual, 0, ',', '.') }}</td>
                    <td class="fw-bold text-success">Rp {{ number_format($trx->total_omzet, 0, ',', '.') }}</td>
                    <td class="fw-bold text-primary">Rp {{ number_format($trx->total_keuntungan, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat penjualan atau barang keluar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
