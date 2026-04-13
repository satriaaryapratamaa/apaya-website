@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm p-4 mb-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Laporan Riwayat Retur</h4>
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak Laporan</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Invoice Referensi</th>
                    <th>Tgl Retur</th>
                    <th>Total Pengembalian Dana / Nilai Barang</th>
                    <th>Alasan</th>
                    <th>Detail Barang Dikembalikan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returs as $idx => $retur)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="fw-bold">{{ $retur->penjualan->nomor_invoice ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('d M Y') }}</td>
                    <td class="fw-bold text-danger">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</td>
                    <td>{{ $retur->alasan_retur }}</td>
                    <td>
                        <ul class="mb-0 ps-3">
                            @foreach($retur->details as $detail)
                                <li>{{ $detail->produk->nama_produk ?? 'Unknown' }} (Qty Retur: {{ $detail->jumlah_retur }})</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat retur.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
