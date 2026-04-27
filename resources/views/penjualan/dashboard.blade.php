@extends('adminlte::page')

@section('title', 'Dashboard Penjualan')

@section('content')
<style>
    body {
        font-family: 'Inter', 'Segoe UI', sans-serif !important;
        background-color: #f4f6f9;
    }
    .db-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #edf2f9;
        margin-bottom: 24px;
        padding: 20px;
    }
    .stat-title { color: #8392a5; font-size: 0.9rem; font-weight: 500; }
    .stat-value { color: #1e293b; font-size: 1.8rem; font-weight: 700; margin: 8px 0; }
    .stat-icon { color: #6366f1; font-size: 1.2rem; }
    .percent-up { color: #10b981; font-size: 0.85rem; font-weight: 600; }
    .percent-down { color: #ef4444; font-size: 0.85rem; font-weight: 600; }
    .percent-neutral { color: #64748b; font-size: 0.85rem; font-weight: 600; }
    
    .card-title-custom {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
    }
    .custom-table th {
        font-weight: 600;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 12px;
        font-size: 0.9rem;
    }
    .custom-table td {
        padding: 16px 8px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #334155;
    }
    .badge-selesai { background-color: #d1fae5; color: #065f46; border-radius: 6px; padding: 5px 10px; font-size: 0.75rem; font-weight: 600; }
    .badge-proses { background-color: #fef3c7; color: #92400e; border-radius: 6px; padding: 5px 10px; font-size: 0.75rem; font-weight: 600; }
    .badge-batal { background-color: #fee2e2; color: #991b1b; border-radius: 6px; padding: 5px 10px; font-size: 0.75rem; font-weight: 600; }
    
    .search-produk {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.9rem;
    }
    .product-list-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }
    .product-list-item:last-child { border-bottom: none; }
    .btn-outline-custom {
        border: 1px solid #e2e8f0;
        color: #475569;
        border-radius: 8px;
        font-weight: 500;
        padding: 6px 16px;
    }
</style>

<div class="container-fluid pt-4" style="min-height: 120vh;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0" style="color: #0f172a">Dashboard Penjualan</h3>
        <div class="d-flex gap-3">
            <button class="btn btn-outline-custom bg-white"><i class="far fa-calendar-alt me-2"></i>30 Hari Terakhir</button>
        </div>
    </div>

    <div class="row">
        
        <div class="col-md-4">
            <div class="db-card">
                <div class="d-flex justify-content-between">
                    <div class="stat-title">Total Penjualan</div>
                    <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                </div>
                <div class="stat-value">Rp {{ number_format($totalJual, 0, ',', '.') }}</div>
                <div>
                    @if($percentJual > 0)
                        <span class="percent-up"><i class="fas fa-arrow-trend-up"></i> +{{ number_format($percentJual, 1) }}%</span>
                    @elseif($percentJual < 0)
                        <span class="percent-down"><i class="fas fa-arrow-trend-down"></i> {{ number_format($percentJual, 1) }}%</span>
                    @else
                        <span class="percent-neutral"><i class="fas fa-minus"></i> 0%</span>
                    @endif
                    <span class="text-muted small ms-1">dari bulan lalu</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="db-card">
                <div class="d-flex justify-content-between">
                    <div class="stat-title">Total Pembelian</div>
                    <div class="stat-icon text-primary"><i class="fas fa-box"></i></div>
                </div>
                <div class="stat-value">Rp {{ number_format($totalBeli, 0, ',', '.') }}</div>
                <div>
                    @if($percentBeli > 0)
                        <span class="percent-up"><i class="fas fa-arrow-trend-up"></i> +{{ number_format($percentBeli, 1) }}%</span>
                    @elseif($percentBeli < 0)
                        <span class="percent-down"><i class="fas fa-arrow-trend-down"></i> {{ number_format($percentBeli, 1) }}%</span>
                    @else
                        <span class="percent-neutral"><i class="fas fa-minus"></i> 0%</span>
                    @endif
                    <span class="text-muted small ms-1">dari bulan lalu</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="db-card">
                <div class="d-flex justify-content-between">
                    <div class="stat-title">Total Keuntungan</div>
                    <div class="stat-icon text-success"><i class="fas fa-dollar-sign"></i></div>
                </div>
                <div class="stat-value">Rp {{ number_format($totalUntung, 0, ',', '.') }}</div>
                <div>
                    @if($percentUntung > 0)
                        <span class="percent-up"><i class="fas fa-arrow-trend-up"></i> +{{ number_format($percentUntung, 1) }}%</span>
                    @elseif($percentUntung < 0)
                        <span class="percent-down"><i class="fas fa-arrow-trend-down"></i> {{ number_format($percentUntung, 1) }}%</span>
                    @else
                        <span class="percent-neutral"><i class="fas fa-minus"></i> 0%</span>
                    @endif
                    <span class="text-muted small ms-1">dari bulan lalu</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-8">
            <div class="db-card h-100 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="card-title-custom mb-0">Grafik Penjualan Bulanan</div>
                    <select class="form-select form-select-sm w-auto bg-light border-0">
                        <option>{{ date('Y') }}</option>
                    </select>
                </div>
                <div style="height: 320px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="db-card h-100 mb-4">
                <div class="card-title-custom">Produk Terlaris</div>
                <div class="mb-3 position-relative">
                    <i class="fas fa-search position-absolute text-muted" style="top: 10px; left: 14px;"></i>
                    <input type="text" class="form-control search-produk w-100" placeholder="Cari produk..." style="padding-left: 40px;">
                </div>
                <div class="mt-3">
                    @forelse($topProducts as $item)
                        <div class="product-list-item">
                            <span class="text-secondary">{{ $item->produk->nama_produk ?? 'Unknown' }}</span>
                            <span class="fw-bold text-primary bg-light px-2 py-1 rounded">{{ $item->total_qty }}</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Belum ada data penjualan</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-7">
            <div class="db-card h-100 mb-4">
                <div class="card-title-custom">Riwayat Transaksi Terakhir</div>
                <div class="table-responsive">
                    <table class="table custom-table table-hover border-transparent">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $trx)
                                <tr>
                                    <td class="fw-bold text-secondary">TRX-{{ date('Y', strtotime($trx->tanggal_penjualan)) }}-{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trx->tanggal_penjualan)->format('d M Y') }}</td>
                                    <td class="fw-bold">Rp {{ number_format($trx->total_omzet, 0, ',', '.') }}</td>
                                    <td>
                                        @if($trx->status == 'lunas')
                                            <span class="badge-selesai">Selesai</span>
                                        @elseif($trx->status == 'hutang')
                                            <span class="badge-proses">Proses</span>
                                        @else
                                            <span class="badge-batal">Batal</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">Belum ada riwayat transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="db-card h-100 mb-4">
                <div class="card-title-custom">Histori Perubahan Stok</div>
                <div class="table-responsive">
                    <table class="table custom-table table-hover border-transparent mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Produk</th>
                                <th>Perubahan</th>
                                <th>Tipe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockMovements as $move)
                                <tr>
                                    <td>
                                        <div>{{ explode(' - ', $move['waktu'])[0] }}</div>
                                        <div class="text-muted small">{{ explode(' - ', $move['waktu'])[1] }}</div>
                                    </td>
                                    <td class="text-secondary">{{ $move['produk'] }}</td>
                                    <td class="fw-bold {{ \Illuminate\Support\Str::startsWith($move['perubahan'], '+') ? 'text-success' : 'text-danger' }}">{{ $move['perubahan'] }}</td>
                                    <td class="text-muted">{{ $move['tipe'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">Belum ada pergerakan stok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const ctx = document.getElementById('salesChart').getContext('2d');
        
        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.25)'); // secondary color opacity
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        const labelBulan = @json($labelBulan ?? []);
        const dataPenjualan = @json($dataPenjualan ?? []);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelBulan,
                datasets: [{
                    label: 'Total Omzet',
                    data: dataPenjualan,
                    borderColor: '#4f46e5',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13, family: 'Inter' },
                        bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#94a3b8', font: { family: 'Inter' } }
                    },
                    y: {
                        grid: { color: '#e2e8f0', borderDash: [5, 5], drawBorder: false },
                        ticks: {
                            color: '#94a3b8', 
                            font: { family: 'Inter' },
                            callback: function(value) {
                                if(value >= 1000000) return (value / 1000000) + 'M';
                                if(value >= 1000) return (value / 1000) + 'K';
                                return value;
                            }
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
