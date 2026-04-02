@extends('layouts.app')

@section('title', 'Daftar Pinjam Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pinjam Ruangan</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <p class="text-muted mb-0">Kelola reservasi ruangan fisik secara cepat untuk kegiatan internal tanpa perlu membuat peserta lengkap.</p>
        </div>
        <div>
            <a href="{{ route('room-bookings.create') }}" class="btn btn-primary shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-plus mr-1"></i> Pesan Ruangan Sekarang
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-xl">
    <div class="card-header bg-white border-bottom-0 py-3 d-flex align-items-center">
        <div class="icon-box-indigo mr-3">
            <i class="fas fa-list"></i>
        </div>
        <h5 class="card-title font-weight-bold mb-0 text-dark">Data Reservasi Ruangan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider">Ruangan</th>
                        <th class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider">Tujuan</th>
                        <th class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider">Waktu</th>
                        <th class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider">Peminjam</th>
                        <th class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider">Status</th>
                        <th class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="px-4 py-3 align-middle font-weight-bold text-dark text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="bg-primary-light p-2 rounded-circle mr-2 d-none d-md-flex" style="background: rgba(16, 185, 129, 0.1)">
                                        <i class="fas fa-door-open text-primary"></i>
                                    </div>
                                    <span>{{ $booking->location }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 align-middle text-center">{{ Str::limit($booking->purpose, 50) }}</td>
                            <td class="px-4 py-3 align-middle text-center">
                                <div class="font-weight-medium">{{ $booking->start_time->format('d M Y') }}</div>
                                <span class="text-xs text-muted bg-light px-2 py-1 rounded d-inline-block mt-1">
                                    <i class="far fa-clock mr-1"></i> {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 align-middle text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="bg-indigo text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-2 text-xs" style="width: 24px; height: 24px;">
                                        {{ strtoupper(substr($booking->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-weight-medium text-dark">{{ $booking->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 align-middle text-center">
                                @if($booking->status == 'booked')
                                    <span class="badge badge-soft-primary px-3 py-1 rounded-pill">Dibooking</span>
                                @elseif($booking->status == 'ongoing')
                                    <span class="badge badge-soft-warning px-3 py-1 rounded-pill">Sedang Dipakai</span>
                                @elseif($booking->status == 'completed')
                                    <span class="badge badge-soft-success px-3 py-1 rounded-pill">Selesai</span>
                                @else
                                    <span class="badge badge-soft-secondary px-3 py-1 rounded-pill">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-middle text-center text-right">
                                @if(auth()->id() == $booking->user_id || auth()->user()->isAdmin())
                                    <form action="{{ route('room-bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus booking ini?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-soft-danger rounded-circle" title="Batalkan/Hapus" style="width: 32px; height: 32px; padding: 0;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <img src="{{ asset('images/empty-state.svg') }}" alt="Empty" style="max-height: 120px; opacity: 0.5;">
                                </div>
                                <h5 class="text-dark font-weight-bold mb-1">Belum ada data peminjaman ruangan</h5>
                                <p class="text-sm mb-3">Tekan tombol di atas untuk membuat reservasi ruangan baru.</p>
                                <a href="{{ route('room-bookings.create') }}" class="btn btn-sm btn-primary rounded-pill px-4">Buat Reservasi Pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bookings->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
