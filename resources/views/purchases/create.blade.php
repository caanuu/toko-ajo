@extends('layouts.app')
@section('title', 'Tambah Pembelian')
@section('page-title', 'Tambah Pembelian')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-dark">Tambah Pembelian</h6>
            <span class="badge bg-white text-muted border">Kode: {{ $generatedCode }}</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('purchases.store') }}" method="POST">
                @csrf
                <input type="hidden" name="code" value="{{ $generatedCode }}">

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-muted small fw-bold">Catatan</label>
                        <input type="text" name="notes" class="form-control" placeholder="">
                    </div>
                </div>

                <div class="table-responsive mb-3 border rounded">
                    <table class="table table-borderless align-middle mb-0" id="cart-table">
                        <thead class="bg-light border-bottom">
                            <tr>
                                <th class="ps-3" width="45%">Produk</th>
                                <th width="15%">Qty</th>
                                <th width="20%">Harga Beli</th>
                                <th width="15%" class="text-end pe-3">Subtotal</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-body">
                            <tr class="cart-row border-bottom">
                                <td class="ps-3 py-3">
                                    <select name="products[0][product_id]" class="form-select product-select" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($products as $prod)
                                            <option value="{{ $prod->id }}">{{ $prod->sku }} - {{ $prod->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3">
                                    <input type="number" name="products[0][qty]" class="form-control qty-input"
                                        min="1" value="1" required>
                                </td>
                                <td class="py-3">
                                    <input type="number" name="products[0][cost]" class="form-control cost-input"
                                        min="0" value="0" required>
                                </td>
                                <td class="text-end pe-3 py-3 fw-bold subtotal-display">0.00</td>
                                <td class="text-center py-3">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i
                                            class="fas fa-times"></i></button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-white">
                            <tr>
                                <td colspan="3" class="text-end fw-bold py-3">Total</td>
                                <td class="text-end fw-bold fs-5 py-3 pe-3 text-dark" id="grand-total">0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" id="add-row"><i class="fas fa-plus me-1"></i>
                        Tambah Baris</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('cart-body');
            const addBtn = document.getElementById('add-row');
            let rowCount = 1;

            function calculateTotals() {
                let total = 0;
                document.querySelectorAll('.cart-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
                    const subtotal = qty * cost;
                    row.querySelector('.subtotal-display').innerText = subtotal.toLocaleString('id-ID');
                    total += subtotal;
                });
                document.getElementById('grand-total').innerText = total.toLocaleString('id-ID');
            }

            tableBody.addEventListener('input', function(e) {
                if (e.target.classList.contains('qty-input') || e.target.classList.contains('cost-input')) {
                    calculateTotals();
                }
            });

            addBtn.addEventListener('click', function() {
                const newRow = document.querySelector('.cart-row').cloneNode(true);
                newRow.querySelector('select').name = `products[${rowCount}][product_id]`;
                newRow.querySelector('select').value = "";
                newRow.querySelector('.qty-input').name = `products[${rowCount}][qty]`;
                newRow.querySelector('.qty-input').value = 1;
                newRow.querySelector('.cost-input').name = `products[${rowCount}][cost]`;
                newRow.querySelector('.cost-input').value = 0;
                newRow.querySelector('.subtotal-display').innerText = "0.00";
                tableBody.appendChild(newRow);
                rowCount++;
            });

            tableBody.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row') && document.querySelectorAll('.cart-row').length > 1) {
                    e.target.closest('tr').remove();
                    calculateTotals();
                }
            });
        });
    </script>
@endsection
