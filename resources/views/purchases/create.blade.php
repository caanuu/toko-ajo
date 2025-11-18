@extends('layouts.app')
@section('title', 'Input Pembelian')
@section('page-title', 'Transaksi Pembelian Baru')

@section('content')
<form action="{{ route('purchases.store') }}" method="POST">
@csrf
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">No. Transaksi</label>
                    <input type="text" name="code" class="form-control bg-light" value="{{ $generatedCode }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <h6 class="mb-0 align-self-center">Daftar Barang</h6>
                <button type="button" class="btn btn-success btn-sm" id="add-row">
                    <i class="fas fa-plus"></i> Tambah Baris
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0" id="product-table">
                    <thead class="table-light">
                        <tr>
                            <th width="40%">Produk</th>
                            <th width="15%">Qty</th>
                            <th>Harga Beli (Satuan)</th>
                            <th width="5%">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="products[0][product_id]" class="form-select form-select-sm" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->sku }} - {{ $prod->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="products[0][qty]" class="form-control form-select-sm qty-input" min="1" value="1" required>
                            </td>
                            <td>
                                <input type="number" name="products[0][cost]" class="form-control form-select-sm cost-input" min="0" value="0" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row" disabled>X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-end">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Simpan Transaksi
                </button>
            </div>
        </div>
    </div>
</div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let rowIndex = 1;
        const tableBody = document.querySelector('#product-table tbody');
        const addBtn = document.getElementById('add-row');

        // Template baris baru
        const productOptions = `
            <option value="">-- Pilih Produk --</option>
            @foreach($products as $prod)
                <option value="{{ $prod->id }}">{{ $prod->sku }} - {{ $prod->name }}</option>
            @endforeach
        `;

        addBtn.addEventListener('click', function () {
            const row = `
                <tr>
                    <td>
                        <select name="products[${rowIndex}][product_id]" class="form-select form-select-sm" required>
                            ${productOptions}
                        </select>
                    </td>
                    <td>
                        <input type="number" name="products[${rowIndex}][qty]" class="form-control form-select-sm" min="1" value="1" required>
                    </td>
                    <td>
                        <input type="number" name="products[${rowIndex}][cost]" class="form-control form-select-sm" min="0" value="0" required>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
            rowIndex++;
        });

        // Fungsi Hapus Baris
        tableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>
@endsection
