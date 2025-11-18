@extends('layouts.app')
@section('title', 'Stok Minimum')
@section('page-title', 'Peringatan Stok Menipis')

@section('content')
<div class="alert alert-danger d-flex align-items-center" role="alert">
    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
    <div>
        <strong>Perhatian!</strong> Daftar produk di bawah ini memiliki stok kurang dari 5 item. Segera lakukan pembelian ulang (restock).
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-danger">Barang Perlu Restock</h5>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-shopping-cart"></i> Beli Barang Sekarang
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Supplier (Terakhir)</th>
                    <th class="text-center">Sisa Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lowStockProducts as $index => $prod)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $prod->sku }}</td>
                    <td><strong>{{ $prod->name }}</strong></td>
                    <td>{{ $prod->category->name }}</td>
                    <td>
                        {{-- Mencari supplier terakhir dari riwayat pembelian --}}
                        @php
                            $lastPurchase = \App\Models\PurchaseDetail::where('product_id', $prod->id)
                                            ->latest()->first();
                        @endphp
                        {{ $lastPurchase ? $lastPurchase->purchase->supplier->name : '-' }}
                    </td>
                    <td class="text-center">
                        <span class="badge bg-danger fs-6">{{ $prod->stock }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-success">
                        <i class="fas fa-check-circle fa-3x mb-2"></i><br>
                        <strong>Aman!</strong> Tidak ada produk yang stoknya menipis.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
