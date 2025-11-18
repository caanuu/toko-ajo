@extends('layouts.app')

@section('title', 'Tambah Supplier')
@section('page-title', 'Registrasi Supplier Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan / Supplier</label>
                        <input type="text" name="name" class="form-control" placeholder="PT. Contoh Jaya" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kontak (No. HP / Telepon)</label>
                        <input type="text" name="contact" class="form-control" placeholder="0812..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Jl. Sudirman No..."></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
