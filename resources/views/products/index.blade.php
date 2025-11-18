@extends('layouts.app')

@section('title', 'Data Produk')
@section('page-title', 'Produk')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
        <div>
            <a href="#" class="btn btn-outline-secondary btn-sm me-2">
                <i class="fas fa-trash"></i> Produk Dihapus
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">SKU</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Unit</th>
                        <th>Stok</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-4 text-muted fw-bold small">{{ $product->sku }}</td>
                        <td class="fw-bold text-dark">{{ $product->name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $product->category->name }}</span></td>
                        <td>pcs</td> <td>
                            <span class="fw-bold {{ $product->stock <= 5 ? 'text-danger' : 'text-success' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="text-end fw-bold">
                            {{ number_format($product->sell_price, 0, ',', '.') }}
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group">
                                <a href="#" class="btn btn-outline-secondary btn-sm" style="font-size: 0.75rem;">Edit</a>
                                <button class="btn btn-outline-danger btn-sm" style="font-size: 0.75rem;">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Belum ada data produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
