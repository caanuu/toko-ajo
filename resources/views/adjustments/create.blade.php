@extends('layouts.app')
@section('title', 'Input Penyesuaian')
@section('page-title', 'Form Penyesuaian Stok')

@section('content')
<form action="{{ route('adjustments.store') }}" method="POST">
@csrf
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">Info Adjustment</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Kode Ref</label>
                    <input type="text" name="code" class="form-control bg-light" value="{{ $generatedCode }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alasan</label>
                    <select name="reason" class="form-control" required>
                        <option value="Stok Opname">Stok Opname (Rutin)</option>
                        <option value="Barang Rusak">Barang Rusak (Expired/Pecah)</option>
                        <option value="Salah Input">Koreksi Salah Input</option>
                        <option value="Lainnya">Lainnya</option>
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
                <h6 class="mb-0 align-self-center">Barang yang Disesuaikan</h6>
                <button type="button" class="btn btn-primary btn-sm" id="add-row">
                    <i class="fas fa-plus"></i> Tambah Barang
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0" id="adj-table">
                    <thead class="table-light">
                        <tr>
                            <th width="50%">Produk</th>
                            <th width="20%">Stok Saat Ini</th>
                            <th width="20%">Perubahan (+/-)</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="products[0][product_id]" class="form-select form-select-sm product-select" required>
                                    <option value="" data-stock="0">-- Pilih Produk --</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}" data-stock="{{ number_format($prod->stock, 0, '', '') }}"> {{-- PERBAIKAN STOK --}}
                                            {{ $prod->name }} (SKU: {{ $prod->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm bg-light current-stock" readonly value="0">
                            </td>
                            <td>
                                <input type="number" name="products[0][qty_change]" class="form-control form-control-sm" placeholder="-2 atau 5" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row" disabled>X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="alert alert-info m-3 text-sm">
                    <small><i class="fas fa-info-circle"></i> Masukkan nilai <strong>NEGATIF</strong> (contoh: -5) untuk mengurangi stok, atau nilai <strong>POSITIF</strong> (contoh: 5) untuk menambah stok.</small>
                </div>
            </div>
            <div class="card-footer bg-white text-end">
                <button type="submit" class="btn btn-warning w-100">
                    <i class="fas fa-save"></i> Simpan Penyesuaian
                </button>
            </div>
        </div>
    </div>
</div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let rowIndex = 1;
        const tableBody = document.querySelector('#adj-table tbody');
        const addBtn = document.getElementById('add-row');

        // PERBAIKAN: Gunakan number_format(..., 0, '', '') di PHP untuk memastikan stock tampil bulat di JS.
        const productOptions = `
            <option value="" data-stock="0">-- Pilih Produk --</option>
            @foreach($products as $prod)
                <option value="{{ $prod->id }}" data-stock="{{ number_format($prod->stock, 0, '', '') }}">
                    {{ $prod->name }} (SKU: {{ $prod->sku }})
                </option>
            @endforeach
        `;

        addBtn.addEventListener('click', function () {
            const row = `
                <tr>
                    <td>
                        <select name="products[${rowIndex}][product_id]" class="form-select form-select-sm product-select" required>
                            ${productOptions}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm bg-light current-stock" readonly value="0">
                    </td>
                    <td>
                        <input type="number" name="products[${rowIndex}][qty_change]" class="form-control form-control-sm" placeholder="+/-" required>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
            rowIndex++;
        });

        // Update info stok saat produk dipilih
        tableBody.addEventListener('change', function (e) {
            if (e.target.classList.contains('product-select')) {
                const stock = e.target.options[e.target.selectedIndex].getAttribute('data-stock');
                const row = e.target.closest('tr');
                row.querySelector('.current-stock').value = stock;
            }
        });

        tableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>
@endsection
