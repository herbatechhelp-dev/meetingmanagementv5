<!-- resources/views/meetings/show.blade.php -->
@extends('layouts.app')

@section('title', $meeting->title)

@section('hide_header', true)

@section('content')
@if($meeting->status === 'ongoing' && ($meeting->organizer_id == auth()->id() || $meeting->assigned_action_taker_id == auth()->id()))
<div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-info-circle mr-2"></i>
    <strong>Meeting sedang berlangsung!</strong> 
    @if($meeting->assigned_action_taker_id == auth()->id())
    Anda ditunjuk sebagai <strong>Penulis Tindak Lanjut</strong>. 
    @endif
    <a href="{{ route('meetings.running', $meeting) }}" class="alert-link font-weight-bold">
        Klik di sini untuk masuk ke halaman meeting dan input tindak lanjut
    </a>
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
@endif

@push('styles')
<style>
    .rounded-xl { border-radius: 12px !important; }
    .bg-emerald-soft { background: rgba(16, 185, 129, 0.08); }
    .text-emerald { color: #10b981; }
    .btn-emerald { background-color: #10b981; color: white; }
    .btn-emerald:hover { background-color: #059669; color: white; }
    .card-premium {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    .badge-soft-success { background: #ecfdf5; color: #059669; border: 1px solid rgba(5, 150, 105, 0.1); }
    .badge-soft-warning { background: #fffbeb; color: #d97706; border: 1px solid rgba(217, 119, 6, 0.1); }
    .badge-soft-emerald { background: #ecfdf5; color: #10b981; border: 1px solid rgba(16, 185, 129, 0.1); }
    .badge-soft-danger { background: #fef2f2; color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.1); }
    
    .timeline-item {
        position: relative;
        padding-left: 30px;
        margin-bottom: 20px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: -20px;
        width: 2px;
        background: #f1f5f9;
    }
    .timeline-dot {
        position: absolute;
        left: -4px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #10b981;
        border: 2px solid white;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    .avatar-stack .avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: 2px solid white;
        margin-left: -10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        font-size: 12px;
        font-weight: bold;
    }
    .avatar-stack .avatar:first-child { margin-left: 0; }
</style>
@endpush


    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5">
        <div class="mb-3 mb-md-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('meetings.index') }}" class="text-emerald font-weight-bold">Rapat</a></li>
                    <li class="breadcrumb-item active text-muted">Detail</li>
                </ol>
            </nav>
            <h1 class="h2 font-weight-bold text-dark mb-1">{{ $meeting->title }}</h1>
            <div class="d-flex align-items-center">
                <span class="badge badge-soft-{{ $meeting->status === 'completed' ? 'success' : ($meeting->status === 'ongoing' ? 'warning' : 'indigo') }} px-3 py-2 rounded-pill font-weight-bold">
                    <i class="fas {{ $meeting->status === 'completed' ? 'fa-check-circle' : ($meeting->status === 'ongoing' ? 'fa-running' : 'fa-calendar-check') }} mr-1"></i>
                    {{ $meeting->status === 'completed' ? 'Selesai' : ($meeting->status === 'ongoing' ? 'Berlangsung' : 'Dijadwalkan') }}
                </span>
                <span class="mx-2 text-muted">•</span>
                <span class="text-muted font-weight-medium">
                    <i class="far fa-calendar-alt mr-1"></i> {{ $meeting->start_time->format('d M Y') }}
                </span>
            </div>
        </div>
        <div class="d-flex align-items-center">
            @if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
                <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-light px-4 py-2 rounded-lg font-weight-bold mr-2 transition-all">
                    <i class="fas fa-edit mr-2 text-emerald"></i> Edit
                </a>
                @if($meeting->status === 'scheduled')
                    <form action="{{ route('meetings.start', $meeting) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-emerald px-4 py-2 rounded-lg font-weight-bold shadow-sm transition-all hover-translate-y">
                            <i class="fas fa-play mr-2"></i> Mulai Rapat
                        </button>
                    </form>
                @endif
            @endif
            @if($meeting->status === 'ongoing')
                @if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
                    <form action="{{ route('meetings.complete', $meeting) }}" method="POST" class="d-inline mr-2" onsubmit="return confirm('Anda yakin ingin mengakhiri rapat ini?');">
                        @csrf
                        <button type="submit" class="btn btn-soft-danger px-4 py-2 rounded-lg font-weight-bold shadow-sm transition-all hover-translate-y">
                            <i class="fas fa-stop mr-2"></i> Selesaikan
                        </button>
                    </form>
                @endif
                @if($meeting->organizer_id == auth()->id() || $meeting->assigned_action_taker_id == auth()->id() || $meeting->assigned_minute_taker_id == auth()->id())
                <a href="{{ route('meetings.running', $meeting) }}" class="btn btn-warning px-4 py-2 rounded-lg font-weight-bold shadow-sm transition-all hover-translate-y">
                    <i class="fas fa-tasks mr-2"></i> Kelola
                </a>
                @endif
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Overview Card -->
            <div class="card card-premium mb-4">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold text-dark mb-4">Ringkasan</h5>
                    <div class="row g-4">
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-emerald-soft p-3 rounded-xl mr-3 text-emerald">
                                    <i class="fas fa-tag fa-lg"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-uppercase text-muted font-weight-bold mb-1 letter-spacing-1">Jenis Rapat</div>
                                    <div class="font-weight-bold text-dark">{{ $meeting->meetingType->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-emerald-soft p-3 rounded-xl mr-3 text-emerald">
                                    <i class="fas fa-user-circle fa-lg"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-uppercase text-muted font-weight-bold mb-1 letter-spacing-1">Penyelenggara</div>
                                    <div class="font-weight-bold text-dark">{{ $meeting->organizer->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-emerald-soft p-3 rounded-xl mr-3 text-emerald">
                                    <i class="fas fa-map-marker-alt fa-lg"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-uppercase text-muted font-weight-bold mb-1 letter-spacing-1">Lokasi</div>
                                    <div class="font-weight-bold text-dark">
                                        @if($meeting->is_online)
                                            <span class="text-primary font-weight-bold">Rapat Online</span>
                                        @else
                                            {{ $meeting->location }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-emerald-soft p-3 rounded-xl mr-3 text-emerald">
                                    <i class="fas fa-clock fa-lg"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-uppercase text-muted font-weight-bold mb-1 letter-spacing-1">Rentang Waktu</div>
                                    <div class="font-weight-bold text-dark">{{ $meeting->start_time->format('H:i') }} — {{ $meeting->end_time->format('H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($meeting->description)
                    <div class="mt-2 p-3 bg-light rounded-lg border-0">
                        <div class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Deskripsi</div>
                        <p class="text-muted mb-0">{{ $meeting->description }}</p>
                    </div>
                    @endif

                    @if($meeting->is_online && $meeting->meeting_link)
                    <div class="mt-4 pt-4 border-top">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Masuk Rapat</h6>
                                <p class="text-sm text-muted mb-0">Gunakan tautan di bawah untuk mengakses ruang rapat virtual.</p>
                            </div>
                            <a href="{{ $meeting->meeting_link }}" target="_blank" class="btn btn-emerald px-4 py-2 rounded-lg font-weight-bold shadow-sm">
                                <i class="fas fa-video mr-2"></i> Gabung Sekarang
                            </a>
                        </div>
                        @if($meeting->meeting_id || $meeting->meeting_password)
                        <div class="mt-3 p-3 bg-emerald-soft rounded-lg d-flex">
                            @if($meeting->meeting_id)
                            <div class="mr-4">
                                <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-1">ID Rapat</span>
                                <code class="text-emerald font-weight-bold">{{ $meeting->meeting_id }}</code>
                            </div>
                            @endif
                            @if($meeting->meeting_password)
                            <div>
                                <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-1">Kode Akses</span>
                                <code class="text-emerald font-weight-bold">{{ $meeting->meeting_password }}</code>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            <div class="card card-premium mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="font-weight-bold text-dark mb-0">Tindak Lanjut</h5>
                        @if($meeting->status === 'completed' && (auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id()))
                            <button type="button" class="btn btn-emerald btn-sm px-3 rounded-pill" data-toggle="modal" data-target="#addActionItemModal">
                                <i class="fas fa-plus mr-1"></i> Tambah Item
                            </button>
                        @endif
                    </div>

                    @if($meeting->actionItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <thead class="text-xs text-uppercase text-muted font-weight-bold letter-spacing-1 bg-light">
                                    <tr>
                                        <th class="py-3 px-4 rounded-left">Tugas</th>
                                        <th class="py-3">Penerima Tugas</th>
                                        <th class="py-3">Batas Waktu</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3 px-4 rounded-right text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($meeting->actionItems as $actionItem)
                                        @php
                                            $isAssignedToMe = $actionItem->assigned_to == auth()->id();
                                            $canView = $isAssignedToMe || auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id();
                                        @endphp
                                        @if($canView)
                                            <tr class="border-bottom">
                                                <td class="py-4 px-4">
                                                    <div class="font-weight-bold text-dark mb-1">{{ Str::limit($actionItem->title, 40) }}</div>
                                                    @if($isAssignedToMe)
                                                        <span class="badge badge-soft-emerald text-xxs px-2 py-1 rounded">Tugas Anda</span>
                                                    @endif
                                                </td>
                                                <td class="py-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-light rounded-circle px-2 py-1 mr-2 text-xs font-weight-bold text-emerald">
                                                            {{ strtoupper(substr($actionItem->assignedTo->name ?? 'U', 0, 1)) }}
                                                        </div>
                                                        <span class="text-sm font-weight-medium text-dark">{{ $actionItem->assignedTo->name ?? 'Tidak Diketahui' }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-4">
                                                    <div class="text-sm {{ $actionItem->isOverdue() ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                                        {{ $actionItem->due_date->format('d M Y') }}
                                                    </div>
                                                </td>
                                                <td class="py-4">
                                                    <span class="badge badge-soft-{{ $actionItem->status === 'completed' ? 'success' : ($actionItem->status === 'in_progress' ? 'warning' : 'danger') }} px-2 py-1 rounded-pill text-xs font-weight-bold">
                                                        {{ $actionItem->status === 'completed' ? 'Selesai' : ($actionItem->status === 'in_progress' ? 'Sedang Berjalan' : ($actionItem->status === 'pending' ? 'Menunggu' : $actionItem->status)) }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-4 text-right">
                                                    <a href="{{ route('action-items.show', $actionItem) }}" class="btn btn-sm btn-light rounded-pill px-3 font-weight-bold text-emerald">Detail</a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 bg-light rounded-xl">
                            <div class="mb-3"><i class="fas fa-tasks fa-3x text-muted opacity-20"></i></div>
                            <h6 class="font-weight-bold text-dark">Belum ada tindak lanjut</h6>
                            <p class="text-muted text-sm px-5">Tugas akan muncul di sini setelah ditambahkan oleh penyelenggara atau notulis.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documentation (Minutes) Card -->
            <div class="card card-premium mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="font-weight-bold text-dark mb-0">Notulensi Rapat</h5>
                        @if($meeting->minutes && $meeting->minutes->is_finalized)
                            <span class="badge badge-soft-success px-3 py-1 rounded-pill font-weight-bold"><i class="fas fa-check-double mr-1"></i> Difinalisasi</span>
                        @else
                            <span class="badge badge-soft-warning px-3 py-1 rounded-pill font-weight-bold"><i class="fas fa-pencil-alt mr-1"></i> Draf</span>
                        @endif
                    </div>

                    @if($meeting->minutes)
                        <div class="p-4 bg-light rounded-xl border-0 mb-4" style="line-height: 1.7; color: #475569;">
                            {!! $meeting->minutes->content !!}
                        </div>

                        @if($meeting->minutes->decisions && count($meeting->minutes->decisions) > 0)
                            <h6 class="font-weight-bold text-dark mb-3">Keputusan Penting</h6>
                            <div class="row g-3 mb-4">
                                @foreach($meeting->minutes->decisions as $decision)
                                    @if(!empty(trim(strip_tags($decision))))
                                        <div class="col-12">
                                            <div class="d-flex align-items-start p-3 bg-white border rounded-xl">
                                                <div class="bg-success-soft p-2 rounded-lg mr-3 text-success">
                                                    <i class="fas fa-check text-sm"></i>
                                                </div>
                                                <span class="text-dark font-weight-medium" style="line-height: inherit;">{!! $decision !!}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 mr-3 font-weight-bold text-emerald" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    {{ strtoupper(substr($meeting->assignedMinuteTaker->name ?? 'M', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-xs text-muted font-weight-bold text-uppercase letter-spacing-1">Notulis</div>
                                    <div class="font-weight-bold text-dark text-sm">{{ $meeting->assignedMinuteTaker->name ?? 'Belum Ditunjuk' }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-muted font-weight-bold text-uppercase letter-spacing-1">Diperbarui Pada</div>
                                <div class="font-weight-bold text-dark text-sm">{{ $meeting->minutes->updated_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5 bg-light rounded-xl">
                            <div class="mb-3 text-muted"><i class="far fa-sticky-note fa-4x opacity-20"></i></div>
                            <h6 class="font-weight-bold text-dark">Belum ada notulensi</h6>
                            <p class="text-muted text-sm mb-4">Dokumentasi resmi belum disiapkan.</p>
                            @if(($meeting->assigned_minute_taker_id == auth()->id() || auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id()) && in_array($meeting->status, ['scheduled', 'ongoing', 'completed']))
                                <a href="{{ route('meetings.running', $meeting) }}#minuteTakerForm" class="btn btn-emerald px-4 py-2 rounded-lg font-weight-bold shadow-sm">
                                    Mulai Dokumentasi
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions Card -->
            <div class="card card-premium mb-4">
                <div class="card-body p-4">
                    <h6 class="text-xs text-uppercase text-muted font-weight-bold mb-3 letter-spacing-1">Aksi Cepat</h6>
                    <div class="d-grid gap-2">
                        @if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
                            <button type="button" class="btn btn-light btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold text-emerald transition-all" data-toggle="modal" data-target="#assignMinuteTakerModal">
                                <i class="fas fa-user-edit mr-2 opacity-50"></i>{{ $meeting->assignedMinuteTaker ? 'Ubah' : 'Tunjuk' }} Notulis
                            </button>
                            
                            @if(in_array($meeting->status, ['scheduled', 'ongoing', 'completed']))
                                <a href="{{ route('meetings.running', $meeting) }}{{ $meeting->minutes ? '' : '#minuteTakerForm' }}" class="btn btn-light btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold text-emerald transition-all">
                                    <i class="fas fa-file-alt mr-2 opacity-50"></i>{{ $meeting->minutes ? 'Edit' : 'Tulis' }} Notulensi
                                </a>
                            @endif

                            <button type="button" class="btn btn-light btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold text-emerald transition-all" data-toggle="modal" data-target="#assignActionTakerModal">
                                <i class="fas fa-user-plus mr-2 opacity-50"></i>{{ $meeting->assignedActionTaker ? 'Ubah' : 'Tunjuk' }} Action Taker
                            </button>
                        @endif
                        
                        @if((auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id() || $meeting->assigned_action_taker_id == auth()->id()) && in_array($meeting->status, ['scheduled', 'ongoing', 'completed']))
                            <button type="button" class="btn btn-light btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold text-emerald transition-all" data-toggle="modal" data-target="#addActionItemModal">
                                <i class="fas fa-plus-circle mr-2 opacity-50"></i>Tambah Tindak Lanjut
                            </button>
                        @endif

                        @php
                            $isOrganizer = $meeting->organizer_id == auth()->id();
                            $isAdmin = auth()->user()->isAdmin();
                            $isParticipant = $meeting->participants->where('user_id', auth()->id())->first();
                        @endphp

                        @if(in_array($meeting->status, ['ongoing', 'completed']))
                            @if($isOrganizer || $isAdmin)
                                <button type="button" class="btn btn-emerald btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold shadow-sm transition-all" data-toggle="modal" data-target="#attendanceModal">
                                    <i class="fas fa-list-check mr-2"></i>Daftar Kehadiran
                                </button>
                            @endif

                            @if($isParticipant && !$isOrganizer && !$isAdmin)
                                <button type="button" class="btn btn-emerald btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold shadow-sm transition-all" data-toggle="modal" data-target="#selfAttendanceModal">
                                    <i class="fas fa-user-check mr-2"></i>Isi Kehadiran
                                </button>
                            @endif
                        @endif

                        @if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
                            <button type="button" class="btn btn-light btn-block text-left py-2 px-3 rounded-lg font-weight-bold text-emerald transition-all" data-toggle="modal" data-target="#uploadFileModal">
                                <i class="fas fa-upload mr-2 opacity-50"></i>Unggah Dokumen
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Attendance List Card -->
            <div class="card card-premium mb-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <h6 class="text-xs text-uppercase text-muted font-weight-bold mb-0 letter-spacing-1">Kehadiran</h6>
                            <span class="badge badge-soft-emerald rounded-pill px-2 py-1 text-xxs font-weight-bold">{{ $meeting->participants->count() }} Total</span>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($meeting->participants as $participant)
                            <div class="list-group-item border-0 py-3 px-4 transition-all hover-bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="bg-emerald-soft rounded-circle mr-3 d-flex align-items-center justify-content-center font-weight-bold text-emerald text-xs" style="width: 38px; height: 38px; min-width: 38px;">
                                        {{ strtoupper(substr($participant->user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="font-weight-bold text-dark text-sm text-truncate">
                                            {{ $participant->user->name }}
                                            @if($participant->user_id == auth()->id()) <span class="text-emerald text-xs ml-1">(Anda)</span> @endif
                                        </div>
                                        <div class="d-flex align-items-center mt-1">
                                            @if($participant->attended === true)
                                                <span class="badge badge-soft-success text-xxs mr-1" title="Hadir"><i class="fas fa-check mr-1"></i></span>
                                            @elseif($participant->attended === false && $participant->excuse)
                                                <span class="badge badge-soft-warning text-xxs mr-1" title="Izin: {{ $participant->excuse }}"><i class="fas fa-info-circle mr-1"></i></span>
                                            @elseif($participant->attended === false)
                                                <span class="badge badge-soft-danger text-xxs mr-1" title="Tidak Hadir"><i class="fas fa-times mr-1"></i></span>
                                            @endif

                                            @if($participant->user_id == $meeting->assigned_minute_taker_id)
                                                <span class="badge badge-soft-warning px-1 py-0 mr-1" style="font-size: 0.6rem;">NOTULIS</span>
                                            @elseif($participant->user_id == $meeting->assigned_action_taker_id)
                                                <span class="badge badge-soft-success px-1 py-0 mr-1" style="font-size: 0.6rem;">ACTION TAKER</span>
                                            @endif
                                            <span class="text-xxs text-muted font-weight-bold px-1 py-0 border rounded">{{ $participant->role === 'chairperson' ? 'CHAIR' : 'MEMBER' }}</span>
                                        </div>
                                    </div>
                                    @if($participant->score)
                                        <div class="badge badge-soft-success text-xxs font-weight-bold ml-2" title="Skor Evaluasi">{{ $participant->score }}</div>
                                    @endif

                                    @if($participant->score_note)
                                        <button type="button" class="btn btn-xs btn-soft-info ml-1 py-0 px-2 rounded-pill font-weight-bold" 
                                                style="font-size: 0.65rem;"
                                                data-toggle="popover" 
                                                data-trigger="focus" 
                                                data-placement="top"
                                                title="Catatan Evaluasi" 
                                                data-content="{{ $participant->score_note }}">
                                            <i class="fas fa-comment-alt mr-1"></i> Catatan
                                        </button>
                                    @endif

                                    @if($meeting->status === 'completed' && (auth()->id() == $meeting->organizer_id || auth()->user()->canManageMeetings()))
                                        <button type="button" class="btn btn-xs btn-soft-primary ml-2 py-0 px-2 rounded-pill font-weight-bold" 
                                                style="font-size: 0.65rem;"
                                                data-toggle="modal" data-target="#rateModal-{{ $participant->id }}">
                                            <i class="fas fa-star mr-1"></i> Nilai
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Files Card -->
            <div class="card card-premium mb-4">
                <div class="card-body p-4">
                    <h6 class="text-xs text-uppercase text-muted font-weight-bold mb-3 letter-spacing-1">Sumber Daya & Berkas</h6>
                    @if($meeting->files->count() > 0)
                        <div class="list-group list-group-flush mx-n4">
                            @foreach($meeting->files as $file)
                                <div class="list-group-item border-0 px-4 py-3 d-flex align-items-center justify-content-between hover-bg-light transition-all rounded-pill mb-2 mx-3 border shadow-sm" style="background: white;">
                                    <div class="d-flex align-items-center overflow-hidden">
                                        <div class="bg-light p-2 rounded mr-3 text-emerald">
                                            <i class="far fa-file-alt"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="font-weight-bold text-dark text-sm text-truncate">{{ $file->file_name }}</div>
                                            <div class="text-xxs text-muted">{{ $file->file_size_formatted }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center ml-2">
                                        <a href="{{ route('meetings.files.download', [$meeting, $file]) }}" class="text-emerald mr-2 hover-translate-y d-inline-block"><i class="fas fa-download"></i></a>
                                        @if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
                                            <form action="{{ route('meetings.files.delete', [$meeting, $file]) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="border-0 bg-transparent text-muted hover-text-danger transition-all p-0" onclick="return confirm('Delete this file?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 bg-light rounded-xl">
                            <i class="far fa-folder-open text-muted opacity-30 fa-2x mb-2"></i>
                            <p class="text-muted text-xs mb-0">Tidak ada dokumen yang dilampirkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

{{-- Rating Modals --}}
        @if(($meeting->organizer_id == auth()->id() || auth()->user()->canManageMeetings()) && $meeting->status === 'completed')
            @foreach($meeting->participants as $participant)
                <div class="modal fade" id="rateModal-{{ $participant->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                            <div class="modal-header border-0 pb-0 pt-4 px-4">
                                <h5 class="font-weight-bold text-dark mb-0">Beri Nilai Performa</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('meetings.participants.rate', [$meeting, $participant]) }}" method="POST">
                                @csrf
                                <div class="modal-body p-4">
                                    <div class="text-center mb-4">
                                        <div class="bg-emerald-soft rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                            <i class="fas fa-user text-emerald fa-lg"></i>
                                        </div>
                                        <h6 class="font-weight-bold text-dark mb-1">{{ $participant->user->name }}</h6>
                                        <span class="text-xs text-muted">{{ $participant->role_label }}</span>
                                    </div>

                                    <div class="form-group mb-4 text-center bg-light p-3 rounded-xl border">
                                        <label class="font-weight-bold text-dark mb-2">Skor (1-100)</label>
                                        <div class="d-flex align-items-center justify-content-center mb-3">
                                            <input type="number" name="score" id="score-{{ $participant->id }}" 
                                                   class="form-control text-center font-weight-bold border-0 bg-white shadow-sm score-number-input" 
                                                   value="{{ $participant->score ?? '' }}" 
                                                   min="1" max="100" required
                                                   style="font-size: 1.5rem; height: 50px; width: 80px; border-radius: 12px; color: #10b981;"
                                                   data-slider-target="slider-{{ $participant->id }}">
                                        </div>
                                        <div class="px-2">
                                            <input type="range" class="custom-range score-slider" 
                                                   id="slider-{{ $participant->id }}" 
                                                   min="1" max="100" 
                                                   value="{{ $participant->score ?? 1 }}"
                                                   data-input-target="score-{{ $participant->id }}">
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark mb-2 text-sm">Catatan Evaluasi</label>
                                        <textarea name="score_note" class="form-control border-0 bg-light rounded-xl p-3" rows="3" placeholder="Umpan balik (opsional)..." style="resize: none;">{{ $participant->score_note }}</textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-emerald btn-block py-3 rounded-xl font-weight-bold shadow-sm transition-all hover-translate-y">
                                        Simpan Nilai
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        {{-- Add Action Item Modal --}}
        <div class="modal fade" id="addActionItemModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header border-0 bg-emerald-soft p-4">
                        <h5 class="modal-title font-weight-bold text-emerald"><i class="fas fa-plus-circle mr-2"></i>Tindak Lanjut Baru</h5>
                        <button type="button" class="close text-emerald" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <form action="{{ route('meetings.action-items.store', $meeting) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-12 mb-3">
                                    <label class="font-weight-bold text-dark text-sm mb-1">Judul Tugas <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control border-0 bg-light rounded-lg p-3" placeholder="Apa yang perlu dilakukan?" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="font-weight-bold text-dark text-sm mb-1">Deskripsi <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control border-0 bg-light rounded-lg p-3" rows="3" placeholder="Berikan deskripsi lebih detail..." required></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark text-sm mb-1">Ditugaskan Ke <span class="text-danger">*</span></label>
                                    <select name="assigned_to" class="form-control" required>
                                        <option value="">Pilih Pengguna</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->department->name ?? 'No Dept' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark text-sm mb-1">Departemen <span class="text-danger">*</span></label>
                                    <select name="department_id" class="form-control border-0 bg-light rounded-lg" required>
                                        <option value="">Pilih Departemen</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark text-sm mb-1">Batas Waktu <span class="text-danger">*</span></label>
                                    <input type="date" name="due_date" class="form-control border-0 bg-light rounded-lg p-3" min="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark text-sm mb-1">Prioritas <span class="text-danger">*</span></label>
                                    <select name="priority" class="form-control border-0 bg-light rounded-lg" required>
                                        <option value="1">🔴 Prioritas Tinggi</option>
                                        <option value="2" selected>🟡 Prioritas Sedang</option>
                                        <option value="3">🟢 Prioritas Rendah</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light px-4 rounded-lg font-weight-bold" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-emerald px-4 rounded-lg font-weight-bold shadow-sm">Buat Tugas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Upload File Modal --}}
        <div class="modal fade" id="uploadFileModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header border-0 bg-emerald-soft p-4">
                        <h5 class="modal-title font-weight-bold text-emerald"><i class="fas fa-upload mr-2"></i>Unggah Berkas</h5>
                        <button type="button" class="close text-emerald" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <form action="{{ route('meetings.files.upload', $meeting) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark text-sm mb-2 d-block">File <span class="text-danger">*</span></label>
                                <div class="p-4 border-2 border-dashed rounded-xl text-center bg-light transition-all hover-translate-y" style="border-color: #cbd5e1;">
                                    <input type="file" name="file" id="file" class="d-none" required>
                                    <label for="file" class="mb-0 cursor-pointer w-100">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                        <div class="font-weight-bold text-dark" id="fileNameDisplay">Pilih berkas atau seret ke sini</div>
                                        <div class="text-xxs text-muted">Mendukung PDF, DOC, XLS, PPT (Maks. 10MB)</div>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold text-dark text-sm mb-2">Deskripsi</label>
                                <textarea name="description" class="form-control border-0 bg-light rounded-lg p-3" rows="2" placeholder="Deskripsi singkat..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light px-4 rounded-lg font-weight-bold" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-emerald px-4 rounded-lg font-weight-bold shadow-sm">Unggah Berkas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Special Modals for Assignment --}}
        @foreach(['Notulis' => 'assignMinuteTakerModal', 'Action Taker' => 'assignActionTakerModal'] as $label => $modalId)
            <div class="modal fade" id="{{ $modalId }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="modal-header border-0 bg-emerald-soft p-4">
                            <h5 class="modal-title font-weight-bold text-emerald"><i class="fas fa-user-check mr-2"></i>Tunjuk {{ $label }}</h5>
                            <button type="button" class="close text-emerald" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <form action="{{ route('meetings.' . ($modalId === 'assignMinuteTakerModal' ? 'assign-minute-taker' : 'assign-action-taker'), $meeting) }}" method="POST">
                            @csrf
                            <div class="modal-body p-4">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark text-sm mb-2 d-block">Pilih Pengguna <span class="text-danger">*</span></label>
                                    <select name="{{ $modalId === 'assignMinuteTakerModal' ? 'minute_taker_id' : 'action_taker_id' }}" class="form-control" required>
                                        <option value="">Pilih Pengguna</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ ($modalId === 'assignMinuteTakerModal' ? $meeting->assigned_minute_taker_id : $meeting->assigned_action_taker_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xxs text-muted mt-2 px-1">Pengguna yang ditunjuk akan memiliki izin khusus untuk mengelola {{ strtolower($label) }} dalam rapat ini.</p>
                                </div>
                                
                                @php
                                    $current = ($modalId === 'assignMinuteTakerModal' ? $meeting->assignedMinuteTaker : $meeting->assignedActionTaker);
                                @endphp
                                @if($current)
                                    <div class="mt-3 p-3 bg-light rounded-lg border d-flex align-items-center">
                                        <div class="bg-indigo text-white rounded-circle mr-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 32px; height: 32px; min-width: 32px; font-size: 10px;">
                                            {{ strtoupper(substr($current->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-xxs text-muted font-weight-bold text-uppercase">Saat Ini Ditunjuk</div>
                                            <div class="font-weight-bold text-dark text-sm">{{ $current->name }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer border-0 p-4 pt-0">
                                <button type="button" class="btn btn-light px-4 rounded-lg font-weight-bold" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-emerald px-4 rounded-lg font-weight-bold shadow-sm">Simpan Penugasan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        @if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
        <div class="modal fade" id="attendanceModal" tabindex="-1">
            <div class="modal-dialog modal-lg border-0 shadow-lg">
                <div class="modal-content border-0" style="border-radius: 20px;">
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-check-double mr-2 text-emerald"></i>Daftar Kehadiran
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover border-0">
                                <thead>
                                    <tr class="text-xxs text-muted text-uppercase font-weight-bold letter-spacing-1">
                                        <th class="border-0 px-0">Peserta</th>
                                        <th class="border-0 text-center">Status</th>
                                        <th class="border-0">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($meeting->participants as $participant)
                                    <tr class="border-bottom">
                                        <td class="border-0 px-0 py-3">
                                            <div class="font-weight-bold text-dark">{{ $participant->user->name }}</div>
                                            <div class="text-xxs text-muted">{{ $participant->user->department->name }}</div>
                                        </td>
                                        <td class="border-0 text-center py-3">
                                            @if($participant->attended === true)
                                                <span class="badge badge-soft-success rounded-pill px-3">Hadir</span>
                                            @elseif($participant->attended === false && $participant->excuse)
                                                <span class="badge badge-soft-warning rounded-pill px-3">Izin</span>
                                            @elseif($participant->attended === false)
                                                <span class="badge badge-soft-danger rounded-pill px-3">Alfa</span>
                                            @else
                                                <span class="badge badge-soft-secondary rounded-pill px-3">Belum Isi</span>
                                            @endif
                                        </td>
                                        <td class="border-0 py-3">
                                            <span class="text-sm text-muted italic">{{ $participant->excuse ?? '-' }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light btn-block py-2 rounded-xl font-weight-bold" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @endif



{{-- Modal Absensi Mandiri untuk Peserta --}}
@php
    $myAttendance = $meeting->participants->where('user_id', auth()->id())->first();
@endphp
<div class="modal fade" id="selfAttendanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="font-weight-bold text-dark mb-0">Isi Kehadiran</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('meetings.attendance.self', $meeting) }}" method="POST" id="selfAttendanceForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="bg-emerald-soft rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-check text-emerald fa-lg"></i>
                        </div>
                        <h6 class="font-weight-bold text-dark mb-1">Halo, {{ auth()->user()->name }}!</h6>
                        <p class="text-xs text-muted">Silakan konfirmasi kehadiran Anda untuk rapat ini.</p>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark mb-3 d-block text-center text-sm">Status Kehadiran</label>
                        <div class="d-flex justify-content-center gap-3">
                            <label class="attendance-option cursor-pointer">
                                <input type="radio" name="attended" value="1" class="d-none" id="radioHadir" {{ $myAttendance && $myAttendance->attended === true ? 'checked' : '' }} required>
                                <div class="attendance-box py-3 px-4 rounded-xl border text-center transition-all">
                                    <i class="fas fa-check-circle mb-2 fa-lg text-emerald"></i>
                                    <div class="font-weight-bold text-dark text-xs">Hadir</div>
                                </div>
                            </label>
                            <label class="attendance-option cursor-pointer ml-3">
                                <input type="radio" name="attended" value="0" class="d-none" id="radioIzin" {{ $myAttendance && $myAttendance->attended === false ? 'checked' : '' }}>
                                <div class="attendance-box py-3 px-4 rounded-xl border text-center transition-all">
                                    <i class="fas fa-clock mb-2 fa-lg text-warning"></i>
                                    <div class="font-weight-bold text-dark text-xs">Izin</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="excuseField" class="form-group mb-4 {{ $myAttendance && $myAttendance->attended === false ? '' : 'd-none' }}">
                        <label class="font-weight-bold text-dark mb-2 text-sm">Alasan / Keterangan</label>
                        <textarea name="excuse" class="form-control border-0 bg-light rounded-xl p-3" rows="3" placeholder="Contoh: Sakit, dinas luar..." style="resize: none;">{{ $myAttendance->excuse ?? '' }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-emerald btn-block py-3 rounded-xl font-weight-bold shadow-sm transition-all hover-translate-y">
                        Simpan Kehadiran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.attendance-option input:checked + .attendance-box {
    background-color: #f0fdf4;
    border-color: #10b981;
    box-shadow: 0 0 0 2px #10b981;
}
.attendance-box:hover {
    background-color: #f8fafc;
}
.cursor-pointer { cursor: pointer; }
.rounded-xl { border-radius: 12px; }
.attendance-box { border-radius: 12px; }
.bg-emerald-soft { background-color: #ecfdf5; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioHadir = document.getElementById('radioHadir');
    const radioIzin = document.getElementById('radioIzin');
    const excuseField = document.getElementById('excuseField');

    if (radioHadir && radioIzin && excuseField) {
        radioHadir.addEventListener('change', function() {
            if (this.checked) excuseField.classList.add('d-none');
        });
        radioIzin.addEventListener('change', function() {
            if (this.checked) excuseField.classList.remove('d-none');
        });
    }
});
</script>
@endsection

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Custom file input display
        document.getElementById('file')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || "Choose file or drag here";
            const display = document.getElementById('fileNameDisplay');
            if (display) display.innerText = fileName;
        });



        // Score Rating Logic
        document.querySelectorAll('.score-slider').forEach(function(slider) {
            slider.addEventListener('input', function() {
                const numberInput = document.getElementById(this.dataset.inputTarget);
                if (numberInput) numberInput.value = this.value;
            });
        });

        document.querySelectorAll('.score-number-input').forEach(function(input) {
            input.addEventListener('input', function() {
                const slider = document.getElementById(this.dataset.sliderTarget);
                let val = parseInt(this.value);
                if (isNaN(val)) return;
                if (val < 1) val = 1; else if (val > 100) val = 100;
                if (slider) slider.value = val;
                this.value = val;
            });
            input.addEventListener('blur', function() {
                if (!this.value || parseInt(this.value) < 1) {
                    this.value = 1;
                    const slider = document.getElementById(this.dataset.sliderTarget);
                    if (slider) slider.value = 1;
                }
            });
        });

        // Initialize Popovers
        $('[data-toggle="popover"]').popover();
    });
</script>
    @endpush