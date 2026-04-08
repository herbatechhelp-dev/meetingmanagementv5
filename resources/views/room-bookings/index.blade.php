@extends('layouts.app')

@section('title', 'Daftar Pinjam Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pinjam Ruangan</li>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center booking-page-head">
            <div>
                <p class="text-muted mb-0">Kelola reservasi ruangan fisik secara cepat untuk kegiatan internal tanpa perlu
                    membuat peserta lengkap.</p>
            </div>
            <div>
                <a href="{{ route('room-bookings.create') }}" class="btn btn-primary shadow-sm"
                    style="border-radius: 10px;" id="createRoomBookingBtn">
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
        <div class="px-3 pt-3 pb-2 border-top booking-toolbar">
            <div class="row align-items-end">
                <div class="col-md-8 mb-2 mb-md-0">
                    <label class="text-xs font-weight-bold text-uppercase text-muted mb-2">Pencarian Cepat</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="bookingQuickSearch" class="form-control border-0 bg-light"
                            placeholder="Cari ruangan, tujuan, nama peminjam, atau waktu...">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="text-xs font-weight-bold text-uppercase text-muted mb-2">Status</label>
                    <select id="bookingQuickStatus" class="form-control bg-light border-0">
                        <option value="">Semua Status</option>
                        <option value="booked">Dibooking</option>
                        <option value="ongoing">Sedang Dipakai</option>
                        <option value="completed">Selesai</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 room-booking-table">
                    <thead class="bg-light">
                        <tr>
                            <th
                                class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider">
                                Ruangan</th>
                            <th
                                class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider">
                                Tujuan</th>
                            <th
                                class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider">
                                Waktu</th>
                            <th
                                class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider">
                                Peminjam</th>
                            <th
                                class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider">
                                Status</th>
                            <th
                                class="border-top-0 border-bottom-0 py-3 px-4 text-center text-xs font-weight-bold text-muted text-uppercase tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr data-status="{{ $booking->status }}">
                                <td class="px-4 py-3 align-middle font-weight-bold text-dark text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="bg-primary-light p-2 rounded-circle mr-2 d-none d-md-flex"
                                            style="background: rgba(16, 185, 129, 0.1)">
                                            <i class="fas fa-door-open text-primary"></i>
                                        </div>
                                        <span>{{ $booking->location }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 align-middle text-center">{{ Str::limit($booking->purpose, 50) }}</td>
                                <td class="px-4 py-3 align-middle text-center">
                                    <div class="font-weight-medium">{{ $booking->start_time->format('d M Y') }}</div>
                                    <span class="text-xs text-muted bg-light px-2 py-1 rounded d-inline-block mt-1">
                                        <i class="far fa-clock mr-1"></i> {{ $booking->start_time->format('H:i') }} -
                                        {{ $booking->end_time->format('H:i') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-middle text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        @php
                                            $display_name = $booking->pic_name ?? ($booking->user->name ?? 'Unknown');
                                            $initial = strtoupper(substr($display_name, 0, 1));
                                        @endphp
                                        <div class="bg-indigo text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-2 text-xs"
                                            style="width: 24px; height: 24px;">
                                            {{ $initial }}
                                        </div>
                                        <span class="text-sm font-weight-medium text-dark">{{ $display_name }}</span>
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
                                        <span
                                            class="badge badge-soft-secondary px-3 py-1 rounded-pill">{{ ucfirst($booking->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 align-middle text-center text-right">
                                    @if(auth()->id() == $booking->user_id || auth()->user()->isAdmin())
                                        <form action="{{ route('room-bookings.destroy', $booking->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus booking ini?');"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger rounded-circle"
                                                title="Batalkan/Hapus" style="width: 32px; height: 32px; padding: 0;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted mobile-empty-state">
                                    <div class="mb-3">
                                        <img src="{{ asset('images/empty-state.svg') }}" alt="Empty"
                                            style="max-height: 120px; opacity: 0.5;">
                                    </div>
                                    <h5 class="text-dark font-weight-bold mb-1">Belum ada data peminjaman ruangan</h5>
                                    <p class="text-sm mb-3">Tekan tombol di atas untuk membuat reservasi ruangan baru.</p>
                                    <a href="{{ route('room-bookings.create') }}"
                                        class="btn btn-sm btn-primary rounded-pill px-4">Buat Reservasi Pertama</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-center py-4 d-none" id="bookingNoSearchResult">
                <div class="mobile-empty-state p-4">
                    <i class="fas fa-search text-muted mb-2"></i>
                    <h6 class="mb-1">Tidak ada hasil pencarian</h6>
                    <small class="text-muted">Ubah kata kunci atau status untuk melihat data lain.</small>
                </div>
            </div>
        </div>
        @if($bookings->hasPages())
            <div class="card-footer bg-white border-0 py-3 mobile-pagination-wrap">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    <div class="mobile-action-spacer d-md-none"></div>
    <div class="mobile-action-bar d-md-none" id="bookingMobileActionBar">
        <a href="{{ route('room-bookings.create') }}" class="mobile-action-btn mobile-action-btn-primary">
            <i class="fas fa-plus-circle"></i>
            <span>Pesan</span>
        </a>
        <button type="button" class="mobile-action-btn" id="bookingMobileStatusBtn">
            <i class="fas fa-filter"></i>
            <span>Status</span>
        </button>
        <button type="button" class="mobile-action-btn" id="bookingMobileSearchBtn">
            <i class="fas fa-search"></i>
            <span>Cari</span>
        </button>
    </div>
@endsection

@section('styles')
    <style>
        @media (max-width: 767.98px) {
            .booking-page-head {
                flex-direction: column;
                align-items: stretch !important;
                gap: 0.85rem;
            }

            #createRoomBookingBtn {
                width: 100%;
            }

            .mobile-action-spacer {
                height: 86px;
            }

            .mobile-action-bar {
                position: fixed;
                left: 12px;
                right: 12px;
                bottom: calc(10px + env(safe-area-inset-bottom));
                z-index: 1060;
                display: flex;
                gap: 0.5rem;
                padding: 0.45rem;
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.94);
                border: 1px solid #dbe7f2;
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
                backdrop-filter: blur(8px);
            }

            .mobile-action-btn {
                flex: 1;
                border: 1px solid #dbe5ef;
                border-radius: 10px;
                padding: 0.45rem 0.35rem;
                background: #f8fbff;
                color: #334155;
                display: inline-flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 0.72rem;
                gap: 0.2rem;
            }

            .mobile-action-btn-primary {
                background: linear-gradient(135deg, #0ea673 0%, #12b981 100%);
                border-color: #0ea673;
                color: #ffffff;
            }

            .mobile-action-btn i {
                font-size: 0.95rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('bookingQuickSearch');
            const statusSelect = document.getElementById('bookingQuickStatus');

            const applyFilters = function () {
                const keyword = (searchInput?.value || '').trim().toLowerCase();
                const selectedStatus = statusSelect?.value || '';
                let visibleCount = 0;

                document.querySelectorAll('.room-booking-table tbody tr').forEach(row => {
                    const isEmptyState = row.querySelector('td[colspan]');
                    if (isEmptyState) {
                        return;
                    }

                    const rowText = row.innerText.toLowerCase();
                    const rowStatus = row.getAttribute('data-status') || '';

                    const matchKeyword = !keyword || rowText.includes(keyword);
                    const matchStatus = !selectedStatus || rowStatus === selectedStatus;

                    const isVisible = matchKeyword && matchStatus;
                    row.style.display = isVisible ? '' : 'none';
                    if (isVisible) {
                        visibleCount++;
                    }
                });

                const noResult = document.getElementById('bookingNoSearchResult');
                if (noResult) {
                    noResult.classList.toggle('d-none', (!keyword && !selectedStatus) || visibleCount > 0);
                }
            };

            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);
            }

            if (statusSelect) {
                statusSelect.addEventListener('change', applyFilters);
            }

            const statusBtn = document.getElementById('bookingMobileStatusBtn');
            const searchBtn = document.getElementById('bookingMobileSearchBtn');

            if (statusBtn && statusSelect) {
                statusBtn.addEventListener('click', function () {
                    statusSelect.focus();
                    statusSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }

            if (searchBtn && searchInput) {
                searchBtn.addEventListener('click', function () {
                    searchInput.focus();
                    searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
        });
    </script>
@endsection