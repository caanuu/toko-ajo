@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Ringkasan')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card h-100 py-2 border-start border-4 border-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Produk</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Product::count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300 text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 py-2 border-start border-4 border-success">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Nilai Persediaan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @php
                                // Hitung total aset (Stok * Harga Jual sementara)
                                $totalAsset = \App\Models\Product::sum(\DB::raw('stock * sell_price'));
                            @endphp
                            Rp {{ number_format($totalAsset, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 py-2 border-start border-4 border-warning">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Produk di Bawah Minimum</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\Product::where('stock', '<=', 5)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 py-2 border-start border-4 border-secondary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Total Qty Stok</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                             {{ number_format(\App\Models\Product::sum('stock'), 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-layer-group fa-2x text-secondary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow mb-4 h-100">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                <h6 class="m-0 font-weight-bold text-dark">Pergerakan Stok 30 Hari (IN vs OUT)</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 320px; position: relative;">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4 h-100">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-dark">Produk Low Stock</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Produk</th>
                            <th>Stok</th>
                            <th>Min</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Product::where('stock', '<=', 5)->take(5)->get() as $item)
                        <tr>
                            <td class="ps-3 small">{{ $item->name }}</td>
                            <td class="text-danger fw-bold">{{ $item->stock }}</td>
                            <td class="text-muted small">5</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4 small">Tidak ada produk menipis.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('stockChart').getContext('2d');
    const stockChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['1 Agu', '5 Agu', '10 Agu', '15 Agu', '20 Agu', '25 Agu', '30 Agu'], // Data dummy sesuai visual
            datasets: [{
                label: 'Masuk (IN)',
                data: [12, 19, 3, 5, 2, 3, 10],
                backgroundColor: '#1cc88a',
            }, {
                label: 'Keluar (OUT)',
                data: [5, 10, 15, 7, 12, 8, 4],
                backgroundColor: '#e74a3b',
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
