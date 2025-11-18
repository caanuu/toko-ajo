@extends('layouts.app')

@section('title', 'Data Supplier')
@section('page-title', 'Manajemen Supplier')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Mitra Supplier</h5>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Supplier
        </a>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Supplier</th>
                    <th>Kontak (HP/Telp)</th>
                    <th>Alamat</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $index => $supplier)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $supplier->name }}</strong></td>
                    <td>{{ $supplier->contact }}</td>
                    <td>{{ $supplier->address ?? '-' }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus supplier ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-truck fa-3x mb-3"></i><br>
                        Belum ada data supplier. Silakan tambah baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
