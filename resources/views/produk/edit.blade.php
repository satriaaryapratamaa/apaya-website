@extends('adminlte::page')

@section('title', 'Edit Produk')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h4 class="fw-bold mb-0">Edit Produk / Master Data</h4>
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

                    <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="nama_produk" class="form-label text-muted fw-bold">Nama Produk</label>
                                <input type="text" name="nama_produk" id="nama_produk" class="form-control bg-light border-0 shadow-none py-2" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="sku" class="form-label text-muted fw-bold">SKU / Kode Barang</label>
                                <input type="text" name="sku" id="sku" class="form-control bg-light border-0 shadow-none py-2" value="{{ old('sku', $produk->sku) }}" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="harga_beli" class="form-label text-muted fw-bold">Harga Beli Lama / Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">Rp</span>
                                    <input type="number" name="harga_beli" id="harga_beli" class="form-control bg-light border-0 shadow-none py-2" value="{{ old('harga_beli', $produk->harga_beli) }}" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="harga_jual" class="form-label text-muted fw-bold">Harga Jual Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">Rp</span>
                                    <input type="number" name="harga_jual" id="harga_jual" class="form-control bg-light border-0 shadow-none py-2" value="{{ old('harga_jual', $produk->harga_jual) }}" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="stok_saat_ini" class="form-label text-muted fw-bold">Penyesuaian Stok</label>
                                <div class="input-group">
                                    <input type="number" name="stok_saat_ini" id="stok_saat_ini" class="form-control bg-light border-0 shadow-none py-2" value="{{ old('stok_saat_ini', $produk->stok_saat_ini) }}" min="0" required>
                                    <span class="input-group-text bg-light border-0">Pcs</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="{{ route('produk.index') }}" class="btn btn-light text-muted">Batal</a>
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
