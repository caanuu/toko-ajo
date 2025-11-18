@extends('layouts.app')
@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('sales.index') }}" method="GET" class="row g-3 align-items-end">
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
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Reset</a>
                <button type="button" onclick="window.print()" class="btn btn-success"><i class="fas fa-print"></i> Print</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Transaksi Keluar</h5>
        <a href="{{ route('sales.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-cash-register"></i> Transaksi Baru
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No Invoice</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Total Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $s)
                <tr>
                    <td><span class="badge bg-dark">{{ $s->code }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($s->date)->format('d M Y') }}</td>
                    <td>{{ $s->customer_name }}</td>
                    <td class="fw-bold">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                    <td>
                        <button class="btn btn-info btn-sm text-white"><i class="fas fa-print"></i> Struk</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Tidak ada data penjualan pada periode ini.</td></tr>
                @endforelse
            </tbody>
            @if($sales->count() > 0)
            <tfoot class="table-light">
                <tr>
                    <th colspan="3" class="text-end">Total Omset Periode Ini:</th>
                    <th colspan="2">Rp {{ number_format($sales->sum('total'), 0, ',', '.') }}</th>
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
