@extends('layouts.app')
@section('title', 'Laporan Stok Minimum')
@section('page-title', 'Laporan Stok Minimum')

@section('content')

    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width: 40px; height: 40px; min-width: 40px;">
            <i class="fas fa-exclamation-triangle fs-5"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0">Peringatan Stok Menipis!</h6>
            <small>Produk di bawah ini telah mencapai batas minimum stok. Segera lakukan pembelian ulang (restock).</small>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h6 class="m-0 fw-bold text-dark">Daftar Barang Perlu Restock</h6>
            <div>
                <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm me-1">
                    <i class="fas fa-shopping-cart me-1"></i> Restock Sekarang
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3" width="5%">No</th>
                            <th class="py-3">Kode Barang</th>
                            <th class="py-3">Nama Barang</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Supplier Terakhir</th>
                            <th class="text-center py-3">Stok Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProducts as $index => $prod)
                            <tr>
                                <td class="ps-4">{{ $index + 1 }}</td>
                                <td class="fw-bold text-muted">{{ $prod->sku }}</td>
                                <td class="fw-bold text-dark">{{ $prod->name }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $prod->category->name }}</span></td>
                                <td>
                                    {{-- Logika ambil supplier terakhir --}}
                                    @php
                                        $lastSup = \App\Models\PurchaseDetail::where('product_id', $prod->id)
                                            ->latest()
                                            ->with('purchase.supplier')
                                            ->first();
                                    @endphp
                                    {{ $lastSup ? $lastSup->purchase->supplier->name : '-' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger fs-6 px-3 py-2">{{ $prod->stock }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-success mb-2"><i class="fas fa-check-circle fa-3x"></i></div>
                                    <h6 class="fw-bold text-muted">Stok Aman!</h6>
                                    <small class="text-muted">Tidak ada produk yang perlu di-restock saat ini.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .sidebar,
            .top-navbar,
            .btn,
            .alert {
                display: none !important;
            }

            .main-content {
                margin: 0 !important;
                padding: 0 !important;
            }

            .card {
                border: none !important;
                shadow: none !important;
            }
        }
    </style>
@endsection
