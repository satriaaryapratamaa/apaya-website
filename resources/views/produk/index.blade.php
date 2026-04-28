@extends('adminlte::page')

@section('title', 'Master Data - Produk')

@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Master Data Produk</h3>
        <a href="{{ route('produk.create') }}" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-1"></i>Tambah Produk Baru</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-muted">SKU / Kode</th>
                            <th class="border-0 py-3 text-muted">Nama Produk</th>
                            <th class="border-0 py-3 text-muted">Stok</th>
                            <th class="border-0 py-3 text-muted">Harga Beli</th>
                            <th class="border-0 py-3 text-muted">Harga Jual</th>
                            <th class="border-0 py-3 text-muted text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produks as $produk)
                        <tr>
                            <td class="px-4 py-3 fw-bold text-secondary">{{ $produk->sku }}</td>
                            <td class="py-3 font-weight-bold text-dark">{{ $produk->nama_produk }}</td>
                            <td class="py-3">
                                @if($produk->stok_saat_ini <= 5)
                                    <span class="badge bg-danger">{{ $produk->stok_saat_ini }} Pcs</span>
                                @else
                                    <span class="badge bg-success">{{ $produk->stok_saat_ini }} Pcs</span>
                                @endif
                            </td>
                            <td class="py-3">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</td>
                            <td class="py-3">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                            <td class="py-3 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-sm btn-outline-primary shadow-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3 text-light"></i><br>
                                Belum ada data produk.<br>
                                Silakan klik tombol <span class="fw-bold">Tambah Produk Baru</span>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
