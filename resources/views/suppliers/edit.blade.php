@extends('layouts.app')
@section('title', 'Edit Supplier')
@section('page-title', 'Edit Data Supplier')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Wajib untuk Update --}}

                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak</label>
                        <input type="text" name="contact" class="form-control" value="{{ $supplier->contact }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="3">{{ $supplier->address }}</textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success text-white">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
