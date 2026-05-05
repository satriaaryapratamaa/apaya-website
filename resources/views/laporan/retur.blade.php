@extends('adminlte::page')

@section('title', 'Laporan Retur Penjualan')

@section('content')
<div class="card border-0 shadow-sm p-4 mb-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Laporan Riwayat Retur Barang</h4>
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak Laporan</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>ID Retur</th>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Qty Retur</th>
                    <th>Tindakan / Kondisi</th>
                    <th>Alasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returs as $idx => $retur)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="fw-bold">RTR-{{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('Ymd') }}-{{ str_pad($retur->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('d M Y') }}</td>
                    <td class="fw-bold">{{ $retur->produk->nama_produk ?? 'Barang Terhapus' }}</td>
                    <td><span class="text-danger fw-bold">{{ $retur->jumlah_retur }} Pcs</span></td>
                    <td>
                        @if($retur->tipe_retur == 'masuk_stok')
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Dikembalikan ke Rak</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Dibuang / Rusak</span>
                        @endif
                    </td>
                    <td>{{ $retur->alasan_retur ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat pengembalian barang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
