@extends('adminlte::page')

@section('title', 'Edit Penjualan')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h4 class="fw-bold mb-0">Edit Penjualan</h4>
                </div>
                <div class="card-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3 border-0">
                            <strong>Ups! Terdapat kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            {{-- Field Tanggal Penjualan --}}
                            <div class="col-md-6">
                                <label for="tanggal_penjualan" class="form-label text-muted fw-bold">Tanggal Penjualan</label>
                                <input type="date" name="tanggal_penjualan" id="tanggal_penjualan" class="form-control bg-light border-0 shadow-none py-2" value="{{ old('tanggal_penjualan', $penjualan->tanggal_penjualan) }}" required>
                            </div>

                            {{-- Field Jumlah Terjual --}}
                            <div class="col-md-6">
                                <label for="jumlah_terjual" class="form-label text-muted fw-bold">Jumlah Terjual</label>
                                <div class="input-group">
                                    <input type="number" name="jumlah_terjual" id="jumlah_terjual" class="form-control bg-light border-0 shadow-none py-2" value="{{ old('jumlah_terjual', $penjualan->jumlah_terjual) }}" min="1" required>
                                    <span class="input-group-text bg-light border-0">Pcs</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold">Produk</label>
                                <input type="text" class="form-control bg-light border-0 py-2" value="{{ $penjualan->produk->nama_produk ?? 'Produk tidak ditemukan' }}" disabled>
                                <small class="text-secondary d-block mt-1">*Produk tidak dapat diubah dari form ini demi konsistensi data stok harian.</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="{{ route('penjualan.index') }}" class="btn btn-light text-muted">Batal</a>
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
