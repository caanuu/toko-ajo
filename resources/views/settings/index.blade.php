@extends('layouts.app')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Aplikasi')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">Profil Toko (Informasi di Struk)</h6>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Nama Toko</label>
                        <input type="text" class="form-control" value="Toko Ajo Asli Store">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" rows="3">Jl. Contoh No. 123, Kota Padang</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" value="0812-3456-7890">
                    </div>
                    <button type="button" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">Akun Pengguna</h6>
            </div>
            <div class="card-body">
                <p>Login sebagai: <strong>{{ Auth::check() ? Auth::user()->name : 'Admin' }}</strong></p>
                <button class="btn btn-outline-warning btn-sm">Ganti Password</button>
            </div>
        </div>

        <div class="card shadow-sm bg-danger text-white">
            <div class="card-body">
                <h6><i class="fas fa-exclamation-triangle"></i> Reset Sistem</h6>
                <p class="small">Hapus semua data transaksi (Pembelian & Penjualan) untuk memulai dari nol.</p>
                <button class="btn btn-light btn-sm text-danger fw-bold" onclick="return confirm('Fitur ini berbahaya! Yakin?')">Reset Data Transaksi</button>
            </div>
        </div>
    </div>
</div>
@endsection
