@extends('layouts.app')
@section('title', 'Kartu Stok')
@section('page-title', 'Laporan Kartu Stok')

@section('content')

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-dark"><i class="fas fa-filter me-2 text-primary"></i>Filter Produk</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('stock-card.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <label class="form-label text-muted small fw-bold">Pilih Produk</label>
                        <select name="product_id" class="form-select select2" required>
                            <option value="">-- Cari Nama / Kode Produk --</option>
                            @foreach ($products as $prod)
                                <option value="{{ $prod->id }}"
                                    {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                                    {{ $prod->sku }} - {{ $prod->name }} (Stok: {{ $prod->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2 mt-3 mt-md-0">
                        <button type="submit" class="btn btn-primary px-4 flex-grow-1">
                            <i class="fas fa-search me-1"></i> Tampilkan
                        </button>
                        <a href="{{ route('stock-card.index') }}" class="btn btn-light border" title="Reset">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (request('product_id') && $selectedProduct)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <div>
                    <h6 class="m-0 fw-bold text-primary">{{ $selectedProduct->name }}</h6>
                    <small class="text-muted">Kode: <strong>{{ $selectedProduct->sku }}</strong> | Kategori:
                        {{ $selectedProduct->category->name }}</small>
                </div>
                <div class="text-end">
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-print me-1"></i> Cetak Kartu
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="bg-light text-center">
                            <tr>
                                <th class="py-3" width="5%">No</th>
                                <th class="py-3" width="15%">Tanggal</th>
                                <th class="py-3">Keterangan / No. Bukti</th>
                                <th class="py-3 text-success" width="10%">Masuk</th>
                                <th class="py-3 text-danger" width="10%">Keluar</th>
                                <th class="py-3 bg-white" width="10%">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $saldo = 0;
                                $no = 1;
                            @endphp

                            {{-- Baris Saldo Awal (Logic Sederhana: jika ada data sebelumnya) --}}
                            @if ($movements->count() > 0)
                                @php
                                    // Hitung saldo sebelum periode ini (jika pakai filter tanggal)
                                    // Untuk sekarang kita anggap mulai dari 0
                                @endphp
                                <tr class="bg-light">
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="fw-bold text-muted">Saldo Awal</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center fw-bold">0</td>
                                </tr>
                            @endif

                            @forelse($movements as $mov)
                                {{-- Hitung Saldo Running Balance --}}
                                @php
                                    if ($mov->movement_type == 'IN') {
                                        $saldo += $mov->qty;
                                    } elseif ($mov->movement_type == 'OUT') {
                                        $saldo -= $mov->qty;
                                    } elseif ($mov->movement_type == 'ADJ') {
                                        // Adjustment bisa nambah/kurang tergantung logic di controller
                                        // Di view ini kita asumsikan qty adjustment tersimpan positif/negatif
                                        // Tapi biasanya di DB 'qty' positif, kita cek after_qty
                                        if ($mov->after_qty > $mov->before_qty) {
                                            $saldo += $mov->qty;
                                        } else {
                                            $saldo -= $mov->qty;
                                        }
                                    }
                                    // Fallback agar sinkron dengan database
                                    $saldo = $mov->after_qty;
                                @endphp

                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($mov->moved_at)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @if ($mov->movement_type == 'IN')
                                            <span class="badge bg-success me-1">BELI</span>
                                        @elseif($mov->movement_type == 'OUT')
                                            <span class="badge bg-danger me-1">JUAL</span>
                                        @else
                                            <span class="badge bg-warning text-dark me-1">ADJ</span>
                                        @endif

                                        <small class="text-muted ms-1">Ref: {{ $mov->ref_id }}</small>

                                        @if ($mov->notes)
                                            <div class="small text-muted fst-italic mt-1">"{{ $mov->notes }}"</div>
                                        @endif
                                    </td>

                                    <td class="text-center fw-bold text-success bg-opacity-10 bg-success">
                                        @if ($mov->movement_type == 'IN' || ($mov->movement_type == 'ADJ' && $mov->after_qty > $mov->before_qty))
                                            {{ $mov->qty }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-center fw-bold text-danger bg-opacity-10 bg-danger">
                                        @if ($mov->movement_type == 'OUT' || ($mov->movement_type == 'ADJ' && $mov->after_qty < $mov->before_qty))
                                            {{ $mov->qty }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-center fw-bold bg-light">
                                        {{ $mov->after_qty }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat transaksi untuk
                                        produk ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-muted small">
                * Data diurutkan berdasarkan tanggal transaksi.
            </div>
        </div>
    @elseif(request('product_id'))
        <div class="alert alert-warning shadow-sm border-0">
            <i class="fas fa-search-minus me-2"></i> Produk tidak ditemukan.
        </div>
    @endif

    <style>
        @media print {

            .sidebar,
            .top-navbar,
            .btn,
            form,
            .card-header button,
            .filter-card {
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
