@extends('layouts.app')
@section('title', 'Kartu Stok')
@section('page-title', 'Laporan Kartu Stok Barang')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('stock-card.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Pilih Produk</label>
                <select name="product_id" class="form-select" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                            {{ $prod->sku }} - {{ $prod->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

@if(request('product_id') && $selectedProduct)
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            Riwayat: <span class="text-primary">{{ $selectedProduct->name }}</span>
            (SKU: {{ $selectedProduct->sku }})
        </h5>
        <small class="text-muted">Stok Saat Ini: <strong>{{ $selectedProduct->stock }}</strong></small>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-striped mb-0">
            <thead class="table-dark text-center">
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan / Ref</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Saldo Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $mov)
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($mov->moved_at)->format('d/m/Y') }}</td>
                    <td>
                        @if($mov->movement_type == 'IN')
                            <span class="badge bg-success">PEMBELIAN</span>
                        @elseif($mov->movement_type == 'OUT')
                            <span class="badge bg-danger">PENJUALAN</span>
                        @elseif($mov->movement_type == 'ADJ')
                            <span class="badge bg-warning text-dark">ADJUSTMENT</span>
                        @endif
                        <br>
                        <small class="text-muted">Ref ID: {{ $mov->ref_id }}</small>
                    </td>

                    <td class="text-end text-success fw-bold">
                        @if($mov->movement_type == 'IN' || ($mov->movement_type == 'ADJ' && $mov->qty > 0 && $mov->after_qty > $mov->before_qty))
                            {{ $mov->qty }}
                        @else
                            -
                        @endif
                    </td>

                    <td class="text-end text-danger fw-bold">
                        @if($mov->movement_type == 'OUT' || ($mov->movement_type == 'ADJ' && $mov->qty > 0 && $mov->after_qty < $mov->before_qty))
                            {{ $mov->qty }}
                        @else
                            -
                        @endif
                    </td>

                    <td class="text-end fw-bold bg-light">
                        {{ $mov->after_qty }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-3">Belum ada pergerakan stok untuk barang ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@elseif(request('product_id'))
    <div class="alert alert-warning">Produk tidak ditemukan.</div>
@endif

@endsection
