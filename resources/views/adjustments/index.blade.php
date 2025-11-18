@extends('layouts.app')
@section('title', 'Penyesuaian Stok')
@section('page-title', 'Riwayat Stok Opname')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Adjustment</h5>
        <a href="{{ route('adjustments.create') }}" class="btn btn-warning btn-sm">
            <i class="fas fa-sliders-h"></i> Buat Penyesuaian
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Alasan</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adjustments as $adj)
                <tr>
                    <td><span class="badge bg-secondary">{{ $adj->code }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($adj->date)->format('d M Y') }}</td>
                    <td>{{ $adj->reason }}</td>
                    <td>{{ $adj->notes ?? '-' }}</td>
                    <td>
                        <a href="{{ route('adjustments.show', $adj->id) }}" class="btn btn-info btn-sm text-white">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Belum ada penyesuaian stok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
