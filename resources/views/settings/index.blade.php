@extends('layouts.app')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header py-3 border-bottom" style="background-color: #f0f9ff;">
            <h6 class="m-0 fw-bold text-dark">Penjualan</h6>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Diskon Default (%)</label>
                        <input type="number" name="default_discount" class="form-control"
                               value="{{ $discount ?? 0 }}" min="0" step="0.1">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Pajak Default (%)</label>
                        <input type="number" name="default_tax" class="form-control"
                               value="{{ $tax ?? 0 }}" min="0" step="0.1">
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center mt-4 text-muted small">
        &copy; 2025 Toko Ajo Asli Store
    </div>
@endsection
