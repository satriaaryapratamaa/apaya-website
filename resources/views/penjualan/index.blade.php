@extends('adminlte::page')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="container-fluid p-4">
        <h2 class="mb-4 fw-bold">Riwayat Penjualan</h2>
        <button class="mb-4 btn btn-primary"><a href="{{route('penjualan.create')}}" class="text-white text-decoration-none">Tambah Penjualan</a></button>
        <div class="card border-0 rounded-3 shadow-sm p-4 mb-4">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Tanggal Penjualan</th>
                            <th>Nama Pelanggan</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penjualans as $p)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td class="text-primary fw-bold">{{$p->nomor_invoice}}</td>
                                <td>{{\Carbon\Carbon::parse($p->tanggal_penjualan)->format('d M Y')}}</td>
                                <td>{{$p->customer_name ?? 'Umum'}}</td>
                                <td>Rp{{number_format($p->details->sum('subtotal'), 0, ',', '.')}}</td>
                                <td>
                                    @if($p->status == 'lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif ($p->status == 'hutang')
                                        <span class="badge bg-warning">Hutang</span>
                                    @else
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="collapse" data-bs-target="#detail-{{$p->id}}">
                                        <i class="bi bi-eye me-2"></i>Detail
                                    </button>
                                    <button class="btn btn-sm btn-secondary text-white" data-bs-toggle="collapse" data-bs-target="#edit-{{$p->id}}">
                                        <a href="#" class="text-white text-decoration-none"><i class="bi bi-pencil-fill me-2"></i>Edit</a>
                                    </button>
                                    <button class="btn btn-sm btn-danger text-white">
                                        <a href="#" class="text-white text-decoration-none"><i class="bi bi-trash-fill me-2"></i>Hapus</a>
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="detail-{{$p->id}}">
                                <td colspan="7" class="bg-light">
                                    <div class="p-3">
                                        <h6>Rincian Produk:</h6>
                                        <table class="table table-bordered table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Produk</th>
                                                    <th>Qty</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($p->details as $detail)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$detail->produk?->nama_produk ?? 'Produk Kosong'}}</td>
                                                        <td>{{$detail->jumlah}}</td>
                                                        <td>Rp{{number_format($detail->harga_satuan, 0, ',', '.')}}</td>
                                                        <td>Rp{{number_format($detail->harga_satuan * $detail->jumlah, 0, ',', '.')}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data penjualan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/javascript.bundle.min.js"></script>
@endsection

