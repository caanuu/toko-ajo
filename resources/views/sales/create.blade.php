@extends('layouts.app')
@section('title', 'Tambah Penjualan')
@section('page-title', 'Tambah Penjualan')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark">Form Transaksi Penjualan</h6>
            <div class="text-muted small">Kode: {{ $generatedCode }}</div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('sales.store') }}" method="POST">
            @csrf
            <input type="hidden" name="code" value="{{ $generatedCode }}">

            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Pelanggan</label>
                    <input type="text" name="customer_name" class="form-control" placeholder="Nama pelanggan (opsional)">
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted">Catatan</label>
                    <input type="text" name="notes" class="form-control" placeholder="Catatan tambahan...">
                </div>
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle" id="cart-table">
                    <thead class="bg-light">
                        <tr>
                            <th width="40%">Produk</th>
                            <th width="15%">Qty</th>
                            <th width="20%">Harga</th>
                            <th width="20%" class="text-end">Subtotal</th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <tbody id="cart-body">
                        <tr class="cart-row">
                            <td>
                                <select name="products[0][product_id]" class="form-select product-select" required>
                                    <option value="" data-price="0">-- Pilih Produk --</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}" data-price="{{ $prod->sell_price }}">
                                            {{ $prod->sku }} - {{ $prod->name }} (Stok: {{ $prod->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="products[0][qty]" class="form-control qty-input" min="1" value="1" required>
                            </td>
                            <td>
                                <input type="number" class="form-control price-input bg-light" readonly value="0">
                            </td>
                            <td class="text-end fw-bold subtotal-display">0.00</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold fs-5" id="grand-total">0.00</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end small align-middle">Diskon (%)</td>
                            <td class="p-1"><input type="number" name="discount" class="form-control form-control-sm text-end" id="discount-input" value="0"></td>
                            <td></td>
                        </tr>
                         <tr>
                            <td colspan="3" class="text-end small align-middle">Pajak (%)</td>
                            <td class="p-1"><input type="number" name="tax" class="form-control form-control-sm text-end" id="tax-input" value="0"></td>
                            <td></td>
                        </tr>
                        <tr class="table-primary">
                            <td colspan="3" class="text-end fw-bold">Grand Total</td>
                            <td class="text-end fw-bold fs-4 text-primary" id="final-total">0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="alert alert-info py-2 small">
                <i class="fas fa-info-circle me-1"></i> Stok tidak boleh minus. Sistem akan menolak jika penjualan melebihi stok.
            </div>

            <div>
                <button type="button" class="btn btn-outline-primary" id="add-row"><i class="fas fa-plus"></i> Tambah Baris</button>
                <button type="submit" class="btn btn-primary px-4">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('cart-body');
    const addBtn = document.getElementById('add-row');
    let rowCount = 1;

    // Fungsi Hitung Total
    function calculateTotals() {
        let total = 0;
        document.querySelectorAll('.cart-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const subtotal = qty * price;

            row.querySelector('.subtotal-display').innerText = subtotal.toLocaleString('id-ID');
            total += subtotal;
        });

        document.getElementById('grand-total').innerText = total.toLocaleString('id-ID');

        // Hitung Diskon & Pajak
        const discountPercent = parseFloat(document.getElementById('discount-input').value) || 0;
        const taxPercent = parseFloat(document.getElementById('tax-input').value) || 0;

        const discountAmount = total * (discountPercent / 100);
        const taxAmount = (total - discountAmount) * (taxPercent / 100);
        const finalTotal = total - discountAmount + taxAmount;

        document.getElementById('final-total').innerText = finalTotal.toLocaleString('id-ID');
    }

    // Event Listener untuk Dropdown Produk & Input Qty
    tableBody.addEventListener('change', function (e) {
        if (e.target.classList.contains('product-select')) {
            const price = e.target.options[e.target.selectedIndex].getAttribute('data-price');
            const row = e.target.closest('tr');
            row.querySelector('.price-input').value = price;
            calculateTotals();
        }
    });

    tableBody.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-input')) {
            calculateTotals();
        }
    });

    document.getElementById('discount-input').addEventListener('input', calculateTotals);
    document.getElementById('tax-input').addEventListener('input', calculateTotals);

    // Tambah Baris
    addBtn.addEventListener('click', function () {
        const firstRow = document.querySelector('.cart-row');
        const newRow = firstRow.cloneNode(true);

        // Reset nilai input di baris baru
        newRow.querySelector('select').name = `products[${rowCount}][product_id]`;
        newRow.querySelector('select').value = "";
        newRow.querySelector('.qty-input').name = `products[${rowCount}][qty]`;
        newRow.querySelector('.qty-input').value = 1;
        newRow.querySelector('.price-input').value = 0;
        newRow.querySelector('.subtotal-display').innerText = "0.00";

        tableBody.appendChild(newRow);
        rowCount++;
    });

    // Hapus Baris
    tableBody.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            if (document.querySelectorAll('.cart-row').length > 1) {
                e.target.closest('tr').remove();
                calculateTotals();
            } else {
                alert("Minimal satu barang harus ada.");
            }
        }
    });
});
</script>
@endsection
