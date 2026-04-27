@extends('adminlte::page')

@section('title', 'Tambah Retur Barang')

@section('content')
<div class="container-fluid p-4">
    <div class="card border-0 shadow-sm p-4 mb-4 rounded-3 text-sm">
        <h4 class="fw-bold mb-4">Pencatatan Retur Barang</h4>
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                Pencatatan gagal:
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('retur.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="produk_id" class="form-label text-muted">Barang yang Diretur</label>
                    <select name="produk_id" id="produk_id" class="form-select bg-light border-0" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($produks as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_produk }} (Harga Jual: Rp {{ number_format($p->harga_jual,0,',','.') }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="jumlah_retur" class="form-label text-muted">Jumlah Retur</label>
                    <input type="number" name="jumlah_retur" id="jumlah_retur" class="form-control bg-light border-0" min="1" value="1" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="tanggal_retur" class="form-label text-muted">Tanggal Retur</label>
                    <input type="date" name="tanggal_retur" id="tanggal_retur" class="form-control bg-light border-0" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12 mb-3">
                    <label for="tipe_retur" class="form-label text-muted">Tindakan / Kondisi Barang</label>
                    <select name="tipe_retur" id="tipe_retur" class="form-select bg-light border-0" required>
                        <option value="masuk_stok">Dikembalikan ke Stok Toko (Kondisi Bagus)</option>
                        <option value="buang_rusak">Dibuang / Rusak (Gagal Jual)</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="alasan_retur" class="form-label text-muted">Keterangan / Alasan Retur</label>
                <textarea name="alasan_retur" id="alasan_retur" rows="3" class="form-control bg-light border-0" placeholder="Contoh: Barang cacat dari pabrik..." required></textarea>
            </div>

            <div class="text-end">
                <a href="{{ route('retur.index') }}" class="btn btn-link text-muted text-decoration-none">Batal</a>
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Simpan Retur</button>
            </div>
        </form>
    </div>
</div>
@endsection
