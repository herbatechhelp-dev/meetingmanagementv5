<!-- resources/views/meetings/index.blade.php -->
@extends('layouts.app')

@section('title', 'Daftar Meeting')

@section('hide_header', true)

@section('content')
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 font-weight-bold text-dark mb-1">Rapat</h1>
            <p class="text-muted mb-0">Kelola dan pantau rapat organisasi Anda.</p>
        </div>
        @if(auth()->user()->canManageMeetings())
        <a href="{{ route('meetings.create') }}" class="btn btn-indigo px-4 py-2 rounded-lg font-weight-bold shadow-sm">
            <i class="fas fa-plus mr-2"></i> Rapat Baru
        </a>
        @endif
    </div>

    <!-- Stats & Tabs Container -->
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body p-0">
            @if(auth()->user()->canManageMeetings())
            <div class="px-4 pt-4">
                <ul class="nav nav-pills custom-pills mb-4" id="meetingTabs" role="tablist">
                    <li class="nav-item mr-2">
                        <a class="nav-link {{ $filters['type'] === 'all' ? 'active shadow-sm' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['type' => 'all']) }}">
                            <i class="fas fa-th-large mr-2"></i> Semua
                            <span class="badge ml-2">{{ $stats['all'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item mr-2">
                        <a class="nav-link {{ $filters['type'] === 'created' ? 'active shadow-sm' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['type' => 'created']) }}">
                            <i class="fas fa-user-edit mr-2"></i> Penyelenggara: Saya
                            <span class="badge ml-2">{{ $stats['created'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $filters['type'] === 'participating' ? 'active shadow-sm' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['type' => 'participating']) }}">
                            <i class="fas fa-user-friends mr-2"></i> Peserta: Saya
                            <span class="badge ml-2">{{ $stats['participating'] ?? 0 }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endif

            <div class="p-4 border-top">
                <form action="{{ route('meetings.index') }}" method="GET" id="filterForm">
                    <input type="hidden" name="type" value="{{ $filters['type'] }}">
                    <div class="row gx-3">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Status</label>
                            <select name="status" class="form-control rounded-lg border-light bg-light">
                                <option value="">Semua Status</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Sedang Berjalan</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Departemen</label>
                            <select name="department_id" class="form-control rounded-lg border-light bg-light">
                                <option value="">Semua Departemen</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Jenis</label>
                            <select name="meeting_type_id" class="form-control rounded-lg border-light bg-light">
                                <option value="">Semua Jenis</option>
                                @foreach($meetingTypes as $type)
                                <option value="{{ $type->id }}" {{ request('meeting_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-indigo btn-block rounded-lg font-weight-bold">
                                <i class="fas fa-filter mr-1"></i> Terapkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="card-body">
        <!-- Summary Cards -->
    <div class="row gx-4 mb-5">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0 bg-white shadow-hover transition-all">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-indigo-soft p-2 rounded-lg mr-3">
                            <i class="fas fa-calendar-alt text-primary"></i>
                        </div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted letter-spacing-1">Total</span>
                    </div>
                    <div class="h3 font-weight-bold mb-0">{{ $meetings->total() }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0 bg-white shadow-hover transition-all">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-blue-soft p-2 rounded-lg mr-3">
                            <i class="far fa-clock text-info"></i>
                        </div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted letter-spacing-1">Dijadwalkan</span>
                    </div>
                    <div class="h3 font-weight-bold mb-0">{{ $statusCounts['scheduled'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0 bg-white shadow-hover transition-all">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-amber-soft p-2 rounded-lg mr-3">
                            <i class="fas fa-running text-warning"></i>
                        </div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted letter-spacing-1">Berjalan</span>
                    </div>
                    <div class="h3 font-weight-bold mb-0">{{ $statusCounts['ongoing'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0 bg-white shadow-hover transition-all">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-emerald-soft p-2 rounded-lg mr-3">
                            <i class="far fa-check-circle text-success"></i>
                        </div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted letter-spacing-1">Selesai</span>
                    </div>
                    <div class="h3 font-weight-bold mb-0">{{ $statusCounts['completed'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 border-0">
                    <thead class="bg-light text-muted text-xs text-uppercase">
                        <tr>
                            <th class="border-0 px-4 py-3">Detail Rapat</th>
                            <th class="border-0 px-4 py-3">Penyelenggara</th>
                            <th class="border-0 px-4 py-3">Lokasi</th>
                            <th class="border-0 px-4 py-3">Status</th>
                            <th class="border-0 px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($meetings as $meeting)
                        @php
                            $isOrganizer = $meeting->organizer_id == auth()->id();
                            $statusColor = match($meeting->status) {
                                'scheduled' => '#4f46e5',
                                'ongoing' => '#f59e0b',
                                'completed' => '#10b981',
                                default => '#64748b'
                            };
                            $statusBg = match($meeting->status) {
                                'scheduled' => 'rgba(79, 70, 229, 0.1)',
                                'ongoing' => 'rgba(245, 158, 11, 0.1)',
                                'completed' => 'rgba(16, 185, 129, 0.1)',
                                default => 'rgba(100, 116, 139, 0.1)'
                            };
                        @endphp
                        <tr class="align-middle transition-all">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded text-center py-2 px-1 mr-3 mb-0" style="min-width: 50px;">
                                        <div class="text-xs font-weight-bold text-uppercase text-muted">{{ $meeting->start_time->format('M') }}</div>
                                        <div class="h5 font-weight-bold mb-0">{{ $meeting->start_time->format('d') }}</div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-1 d-flex align-items-center">
                                            {{ Str::limit($meeting->title, 40) }}
                                            @if($isOrganizer)
                                            <span class="ml-2 badge bg-indigo-soft text-indigo text-xs px-2 py-1">Anda</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-muted">
                                            <i class="far fa-clock mr-1 opacity-50"></i> {{ $meeting->start_time->format('H:i') }} • {{ $meeting->meetingType->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle font-weight-bold d-flex align-items-center justify-content-center mr-2 text-xs" style="width: 28px; height: 28px;">
                                        {{ strtoupper(substr($meeting->organizer->name, 0, 1)) }}
                                    </div>
                                    <div class="text-sm">
                                        <div class="font-weight-bold text-dark">{{ $meeting->organizer->name }}</div>
                                        <div class="text-xs text-muted">{{ $meeting->department->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-dark">
                                    @if($meeting->is_online)
                                    <span class="text-primary"><i class="fas fa-video mr-1"></i> Online</span>
                                    @else
                                    <span class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> {{ Str::limit($meeting->location, 25) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge" style="background: {{ $statusBg }}; color: {{ $statusColor }}; border-radius: 6px; font-weight: 600; font-size: 11px;">
                                    @php
                                        $statusLabel = match($meeting->status) {
                                            'scheduled' => 'DIJADWALKAN',
                                            'ongoing' => 'BERJALAN',
                                            'completed' => 'SELESAI',
                                            default => strtoupper($meeting->status)
                                        };
                                    @endphp
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="btn-group">
                                    <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-sm btn-light rounded-circle mr-1" title="View">
                                        <i class="fas fa-eye text-xs text-muted"></i>
                                    </a>
                                    @if(auth()->user()->canManageMeetings() || $isOrganizer)
                                    <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-sm btn-light rounded-circle mr-1" title="Edit">
                                        <i class="fas fa-edit text-xs text-muted"></i>
                                    </a>
                                    <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light rounded-circle" onclick="return confirm('Arsipkan rapat ini?')" title="Hapus">
                                            <i class="fas fa-trash text-xs text-danger"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="mb-3 opacity-20"><i class="fas fa-calendar-times fa-4x"></i></div>
                                <h5 class="text-muted">Tidak ada rapat ditemukan</h5>
                                <p class="text-sm text-muted">Coba sesuaikan filter Anda atau buat rapat baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 px-2">
        {{ $meetings->links() }}
    </div>
</div>
    </div>
@endsection

@section('styles')
<style>
    .custom-pills .nav-link {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 13px;
        padding: 10px 20px;
        border-radius: 10px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .custom-pills .nav-link.active {
        background: white !important;
        color: #4f46e5 !important;
        border-color: #e2e8f0;
    }
    .custom-pills .badge {
        background: rgba(79, 70, 229, 0.1);
        color: #4f46e5;
        font-weight: 700;
    }
    .custom-pills .nav-link.active .badge {
        background: #4f46e5;
        color: white;
    }
    .shadow-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }
    .bg-indigo-soft { background: rgba(79, 70, 229, 0.1); }
    .bg-blue-soft { background: rgba(56, 189, 248, 0.1); }
    .bg-amber-soft { background: rgba(245, 158, 11, 0.1); }
    .bg-emerald-soft { background: rgba(16, 185, 129, 0.1); }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll ke atas saat pindah halaman
        document.querySelectorAll('.pagination .page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.closest('.page-item').classList.contains('disabled') &&
                    !this.closest('.page-item').classList.contains('active')) {
                    setTimeout(() => {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }, 100);
                }
            });
        });

        // Update hidden type input ketika tab diklik
        document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function() {
                const url = new URL(this.href);
                const type = url.searchParams.get('type') || 'all';
                document.querySelector('input[name="type"]').value = type;
            });
        });
    });
</script>
@endsection