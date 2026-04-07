@extends('layouts.app')

@section('title', 'Manajemen Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Manajemen Ruangan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold mb-0">Daftar Ruangan</h3>
                        <a href="{{ route('rooms.create') }}" class="btn btn-primary shadow-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Ruangan
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-muted text-uppercase small font-weight-bold" style="width: 50px;">#</th>
                                    <th class="px-4 py-3 text-muted text-uppercase small font-weight-bold">Nama Ruangan</th>
                                    <th class="px-4 py-3 text-muted text-uppercase small font-weight-bold text-center">Kapasitas</th>
                                    <th class="px-4 py-3 text-muted text-uppercase small font-weight-bold">Deskripsi</th>
                                    <th class="px-4 py-3 text-muted text-uppercase small font-weight-bold text-center">Status</th>
                                    <th class="px-4 py-3 text-muted text-uppercase small font-weight-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rooms as $room)
                                <tr>
                                    <td class="px-4 py-3 text-center">{{ $loop->iteration + ($rooms->currentPage() - 1) * $rooms->perPage() }}</td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary-soft p-2 rounded mr-3 text-primary">
                                                <i class="fas fa-door-open"></i>
                                            </div>
                                            <span class="font-weight-600">{{ $room->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge badge-light px-3 py-2" style="border-radius: 8px;">
                                            <i class="fas fa-users mr-1 text-muted"></i> {{ $room->capacity ?? '-' }} Orang
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-muted small">{{ Str::limit($room->description, 50) ?: '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($room->is_active)
                                            <span class="badge badge-pill bg-emerald-soft text-emerald font-weight-bold px-3 py-1">Aktif</span>
                                        @else
                                            <span class="badge badge-pill bg-danger-soft text-danger font-weight-bold px-3 py-1">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="btn-group">
                                            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-soft-info mr-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-soft-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-door-closed fa-3x text-muted mb-3 opacity-25"></i>
                                            <p class="text-muted">Belum ada data ruangan.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($rooms->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $rooms->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(79, 70, 229, 0.1); }
    .bg-emerald-soft { background-color: rgba(16, 185, 129, 0.1); }
    .text-emerald { color: #10b981; }
    .bg-danger-soft { background-color: rgba(239, 68, 68, 0.1); }
    .font-weight-600 { font-weight: 600; }
</style>
@endsection
