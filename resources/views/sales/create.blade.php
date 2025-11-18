@extends('layouts.app')
@section('title', 'Tambah Penjualan')
@section('page-title', 'Tambah Penjualan')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-dark">Tambah Penjualan</h6>
            <span class="badge bg-white text-muted border">Kode: {{ $generatedCode }}</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                <input type="hidden" name="code" value="{{ $generatedCode }}">

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Pelanggan</label>
                        <input type="text" name="customer_name" class="form-control"
                            placeholder="Nama pelanggan (opsional)">
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
                                <th width="20%">Harga</th>
                                <th width="15%" class="text-end pe-3">Subtotal</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-body">
                            <tr class="cart-row border-bottom">
                                <td class="ps-3 py-3">
                                    <select name="products[0][product_id]" class="form-select product-select" required>
                                        <option value="" data-price="0">-- Pilih Produk --</option>
                                        @foreach ($products as $prod)
                                            <option value="{{ $prod->id }}" data-price="{{ $prod->sell_price }}">
                                                {{ $prod->sku }} - {{ $prod->name }} (Stok: {{ $prod->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3">
                                    <input type="number" name="products[0][qty]" class="form-control qty-input text-center"
                                        min="1" value="1" required>
                                </td>
                                <td class="py-3">
                                    {{-- PERBAIKAN: Input harga Jual dibuat EDITABLE --}}
                                    <input type="number" name="products[0][unit_price]"
                                        class="form-control price-input text-end" value="0" min="0" required>
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
                                <td colspan="3" class="text-end fw-bold py-2">Subtotal</td>
                                <td class="text-end fw-bold py-2 pe-3" id="total-subtotal-display">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold py-2">Diskon</td>
                                <td class="text-end fw-bold py-2 pe-3" id="discount-amount-display">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold py-2">Pajak</td>
                                <td class="text-end fw-bold py-2 pe-3" id="tax-amount-display">0.00</td>
                                <td></td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="3" class="text-end fw-bold py-2 fs-5">Total</td>
                                <td class="text-end fw-bold py-2 pe-3 fs-5" id="final-total-display">0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Diskon (%)</label>
                        <input type="number" name="discount_percent" class="form-control" id="discount-percent-input"
                            value="{{ $defaultDiscount ?? 0 }}" min="0" max="100">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Pajak (%)</label>
                        <input type="number" name="tax_percent" class="form-control" id="tax-percent-input"
                            value="{{ $defaultTax ?? 0 }}" min="0" max="100">
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="alert alert-info border-0 shadow-sm mb-0 p-2 w-100" role="alert">
                            Stok tidak boleh minus. Sistem akan menolak jika penjualan melebihi stok.
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" id="add-row"><i
                            class="fas fa-plus me-1"></i> Tambah Baris</button>
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

            // Helper: Format Rupiah
            const formatRupiah = (num) => {
                if (isNaN(num) || num === null) return "0.00";
                return parseFloat(num).toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });
            };

            function calculateTotals() {
                let currentSubtotal = 0;

                // 1. Hitung Subtotal Awal
                document.querySelectorAll('.cart-row').forEach(row => {
                    // Ambil harga dari input yang sekarang EDITABLE (.price-input)
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const subtotalItem = qty * price;

                    row.querySelector('.subtotal-display').innerText = formatRupiah(subtotalItem);
                    currentSubtotal += subtotalItem;
                });

                // 2. Ambil persentase diskon dan pajak
                const discountPercent = parseFloat(document.getElementById('discount-percent-input').value) || 0;
                const taxPercent = parseFloat(document.getElementById('tax-percent-input').value) || 0;

                // 3. Hitung nilai diskon
                const discountAmount = currentSubtotal * (discountPercent / 100);

                // 4. Hitung Total Setelah Diskon (Basis untuk perhitungan Pajak)
                const totalAfterDiscount = currentSubtotal - discountAmount;

                // 5. Hitung nilai Pajak (dihitung dari total setelah diskon)
                const taxAmount = totalAfterDiscount * (taxPercent / 100);

                // 6. Hitung Total Akhir
                const finalTotal = totalAfterDiscount + taxAmount;

                // Update tampilan di footer tabel
                document.getElementById('total-subtotal-display').innerText = formatRupiah(currentSubtotal);
                document.getElementById('discount-amount-display').innerText = formatRupiah(discountAmount);
                document.getElementById('tax-amount-display').innerText = formatRupiah(taxAmount);
                document.getElementById('final-total-display').innerText = formatRupiah(finalTotal);

                // Tambahkan hidden input untuk menyimpan nilai diskon dan pajak dalam nominal ke backend
                if (!document.querySelector('input[name="discount"]')) {
                    const hiddenDiscount = document.createElement('input');
                    hiddenDiscount.type = 'hidden';
                    hiddenDiscount.name = 'discount';
                    document.querySelector('form').appendChild(hiddenDiscount);
                }
                document.querySelector('input[name="discount"]').value = discountAmount.toFixed(2);

                if (!document.querySelector('input[name="tax"]')) {
                    const hiddenTax = document.createElement('input');
                    hiddenTax.type = 'hidden';
                    hiddenTax.name = 'tax';
                    document.querySelector('form').appendChild(hiddenTax);
                }
                document.querySelector('input[name="tax"]').value = taxAmount.toFixed(2);
            }

            // Event 1: Ganti Produk (Dropdown)
            tableBody.addEventListener('change', function(e) {
                if (e.target.classList.contains('product-select')) {
                    const option = e.target.options[e.target.selectedIndex];
                    const price = parseFloat(option.getAttribute('data-price'));

                    const row = e.target.closest('tr');
                    // Saat produk diganti, isi harga jual awal ke input harga yang EDITABLE
                    row.querySelector('.price-input').value = price;

                    calculateTotals();
                }
            });

            // Event 2: Ubah Qty ATAU Harga
            tableBody.addEventListener('input', function(e) {
                // Sekarang mendengarkan perubahan Qty dan Price
                if (e.target.classList.contains('qty-input') || e.target.classList.contains(
                    'price-input')) {
                    calculateTotals();
                }
            });

            // Event 3: Ubah Diskon (%) / Pajak (%)
            document.getElementById('discount-percent-input').addEventListener('input', calculateTotals);
            document.getElementById('tax-percent-input').addEventListener('input', calculateTotals);

            // Event 4: Tambah Baris Baru
            addBtn.addEventListener('click', function() {
                const template = document.querySelector('.cart-row');
                const newRow = template.cloneNode(true);

                // Reset nilai dan set nama unik
                newRow.querySelector('select').name = `products[${rowCount}][product_id]`;
                newRow.querySelector('select').value = "";

                // Input harga yang sekarang EDITABLE
                newRow.querySelector('.price-input').name = `products[${rowCount}][unit_price]`;
                newRow.querySelector('.price-input').value = 0;

                newRow.querySelector('.qty-input').name = `products[${rowCount}][qty]`;
                newRow.querySelector('.qty-input').value = 1;

                newRow.querySelector('.subtotal-display').innerText = "0.00";

                tableBody.appendChild(newRow);
                rowCount++;
                calculateTotals();
            });

            // Event 5: Hapus Baris
            tableBody.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    if (document.querySelectorAll('.cart-row').length > 1) {
                        e.target.closest('tr').remove();
                        calculateTotals();
                    } else {
                        alert("Minimal satu produk harus dipilih.");
                    }
                }
            });

            calculateTotals();
        });
    </script>
@endsection
