@extends('layouts.app')
@section('title', 'Laporan Pembelian')
@section('page-title', 'Laporan Pembelian')

@section('content')

    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark">Laporan Pembelian</h6>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form action="{{ route('purchases.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label text-muted small fw-bold">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ request('start_date', date('Y-m-01')) }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-muted small fw-bold">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ request('end_date', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>
                            Tampil</button>
                        <a href="{{ route('purchases.index') }}" class="btn btn-light border" title="Reset"><i
                                class="fas fa-undo"></i></a>
                        <button type="button" onclick="window.print()" class="btn btn-outline-secondary" title="Print"><i
                                class="fas fa-print"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Jumlah Dokumen</div>
                    <h3 class="fw-bold text-dark mb-0">{{ $summary['count'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Subtotal</div>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Diskon</div>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($summary['discount'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total</div>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($summary['total'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Tanggal</th>
                            <th class="py-3">Kode</th>
                            <th class="py-3">Supplier</th>
                            <th class="text-end py-3">Subtotal</th>
                            <th class="text-end py-3">Diskon</th>
                            <th class="text-end py-3">Pajak</th>
                            <th class="text-end pe-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $p)
                            <tr>
                                <td class="ps-4">{{ \Carbon\Carbon::parse($p->date)->format('d/m/Y') }}</td>
                                <td class="fw-bold text-primary">{{ $p->code }}</td>
                                <td>{{ $p->supplier->name }}</td>
                                <td class="text-end">Rp {{ number_format($p->subtotal, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($p->discount, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($p->tax, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold pe-4">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted bg-light">Tidak ada data pada periode
                                    ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-dark">Top 10 Produk Dibeli</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Produk</th>
                            <th class="text-center py-3">Qty</th>
                            <th class="text-end pe-4 py-3">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $top)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $top->product->name }}</td>
                                <td class="text-center">{{ $top->total_qty }}</td>
                                <td class="text-end pe-4">Rp {{ number_format($top->total_value, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
