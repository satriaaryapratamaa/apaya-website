@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm p-4 mb-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Riwayat Retur Penjualan</h4>
        <a href="{{ route('retur.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Retur</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID Retur</th>
                    <th>Invoice Penjualan</th>
                    <th>Tanggal Retur</th>
                    <th>Total Retur</th>
                    <th>Alasan</th>
                    <th>Detail Barang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returs as $retur)
                <tr>
                    <td>#{{ $retur->id }}</td>
                    <td>{{ $retur->penjualan->nomor_invoice ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('d M Y') }}</td>
                    <td class="text-danger fw-bold">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</td>
                    <td>{{ $retur->alasan_retur ?? '-' }}</td>
                    <td>
                        <ul class="mb-0 ps-3">
                            @foreach($retur->details as $detail)
                                <li>{{ $detail->produk->nama_produk ?? 'Unknown' }} (Qty: {{ $detail->jumlah_retur }})</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data retur.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
