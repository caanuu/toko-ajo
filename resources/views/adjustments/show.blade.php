@extends('layouts.app')
@section('title', 'Detail Penyesuaian')
@section('page-title', 'Detail Stok Opname')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <strong>Kode: {{ $adjustment->code }}</strong>
        <span class="float-end text-muted">{{ $adjustment->date->format('d M Y') }}</span>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr><td width="100">Alasan</td><td>: {{ $adjustment->reason }}</td></tr>
                    <tr><td>Catatan</td><td>: {{ $adjustment->notes ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Perubahan Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adjustment->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->product->name }} ({{ $detail->product->sku }})</td>
                    <td class="{{ $detail->qty_change < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                        {{ $detail->qty_change > 0 ? '+' : '' }}{{ $detail->qty_change }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('adjustments.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
@endsection
