@extends('adminlte::page')

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
                    <th>Barang Diretur</th>
                    <th>Jumlah</th>
                    <th>Tindakan / Kondisi</th>
                    <th>Tanggal Retur</th>
                    <th>Alasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returs as $retur)
                <tr>
                    <td>#{{ $retur->id }}</td>
                    <td class="fw-bold">{{ $retur->produk->nama_produk ?? 'Barang Terhapus' }}</td>
                    <td>{{ $retur->jumlah_retur }} Pcs</td>
                    <td>
                        @if($retur->tipe_retur == 'masuk_stok')
                            <span class="badge bg-success">Dikembalikan ke Stok</span>
                        @else
                            <span class="badge bg-danger">Dibuang / Rusak</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('d M Y') }}</td>
                    <td>{{ $retur->alasan_retur ?? '-' }}</td>
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
