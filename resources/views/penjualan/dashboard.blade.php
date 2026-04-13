@extends('layouts.app')

@section('content')
    <div class="container-fluid p-4" style="background-color: #f8f9faa; min-height: 160vh;">
        <h3 class="mb-4 fw-bold">Dashboard Penjualan</h3>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-3 rounded-3">
                    <div class="text-muted small">Total Penjualan Bulan Ini</div>
                    <div class="fs-4 fw-bold">{{number_format($totalJual ?? 0,0, ',', '.')}}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-3 rounded-3">
                    <div class="text-muted small">Total Retur Bulan Ini</div>
                    <div class="fs-4 fw-bold text-danger">{{number_format($totalRetur ?? 0,0, ',', '.')}}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm p-4 mb-4 rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">Grafik Penjualan Bulan ini</h6>
                <div style="height: 300px">
                    <canvas id="barChart" height="800" width="2000"></canvas>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col md-8">
                <div class="card border-0 shadow-sm p-4 rounded-3">
                    <h6 class="fw-bold mb-4">Grafik Produk Terlaris</h6>
                    <div style="height: 300px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col md-4">
                <div class="card border-0 shadow-sm p-4 rounded-3 d-flex flex-column">
                    <h6 class="fw-bold mb-4">Pencatatan Retur Penjualan</h6>
                    {{-- <div class="text-center my-auto text-muted small">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <p>Kelola retur</p>
                    </div> --}}
                    <div class="text-end mt-auto">
                        <a href="#" class="text-decoration-none small fw-bold">Detail Laporan <i class="fas fa-chevron-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labelBulan = @json($labelBulan ?? ['januari', 'februari', 'maret']);
        const dataPenjualan = @json($dataPenjualan ?? [100, 5, 0]);

        const labelProduk = @json($labelProduk ?? ['Produk A', 'Produk B']);
        const dataProduk = @json($dataProduk ?? [10, 20]);

        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: labelBulan,
                datasets: [{
                    label: 'Total Penjualan',
                    data: dataPenjualan,
                    backgroundColor: '#9fa8da',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        })

        new Chart(document.getElementById('pieChart'), {
            type: 'doughnut',
            data: {
                labels: labelProduk,
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: dataProduk,
                    backgroundColor: ['#7986cb', '#9fa8da', '#c5cae9', '#e8eaf6'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        })
    </script>
@endsection
