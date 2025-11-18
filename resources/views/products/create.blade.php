@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('page-title', 'Input Produk Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode SKU (Barcode)</label>
                            <input type="text" name="sku" class="form-control" placeholder="Cth: BRG-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="name" class="form-control" placeholder="Cth: Kemeja Polos" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok Awal</label>
                            <input type="number" name="stock" class="form-control" value="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga Jual (Rp)</label>
                        <input type="number" name="sell_price" class="form-control" placeholder="0" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan Produk</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
