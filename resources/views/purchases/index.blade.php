@extends('layouts.app')
@section('title', 'Laporan Pembelian')
@section('page-title', 'Laporan Pembelian')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('purchases.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Tampilkan</button>
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Reset</a>
                <button type="button" onclick="window.print()" class="btn btn-success"><i class="fas fa-print"></i> Print</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Transaksi Masuk</h5>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Transaksi Baru
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No PO</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Total Belanja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $p)
                <tr>
                    <td><span class="badge bg-secondary">{{ $p->code }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($p->date)->format('d M Y') }}</td>
                    <td>{{ $p->supplier->name }}</td>
                    <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm text-white"><i class="fas fa-eye"></i> Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Tidak ada data pada periode ini.</td></tr>
                @endforelse
            </tbody>
            @if($purchases->count() > 0)
            <tfoot class="table-light">
                <tr>
                    <th colspan="3" class="text-end">Total Periode Ini:</th>
                    <th colspan="2">Rp {{ number_format($purchases->sum('total'), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

<style>
    @media print {
        .sidebar, .btn, form { display: none !important; }
        .content { width: 100% !important; margin: 0 !important; padding: 0 !important; }
    }
</style>
@endsection
