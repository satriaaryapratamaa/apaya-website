@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm p-4 mb-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Laporan Riwayat Penjualan</h4>
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak Laporan</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>Tgl Penjualan</th>
                    <th>Nama Customer</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th>Detail Barang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualans as $idx => $trx)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="fw-bold">{{ $trx->nomor_invoice }}</td>
                    <td>{{ \Carbon\Carbon::parse($trx->tanggal_penjualan)->format('d M Y') }}</td>
                    <td>{{ $trx->customer_name ?? '-' }}</td>
                    <td class="fw-bold text-success">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $trx->status == 'lunas' ? 'bg-success' : ($trx->status == 'hutang' ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ ucfirst($trx->status) }}
                        </span>
                    </td>
                    <td>
                        <ul class="mb-0 ps-3">
                            @foreach($trx->details as $detail)
                                <li>{{ $detail->produk->nama_produk ?? 'Unknown' }} (Qty: {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }})</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat penjualan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
