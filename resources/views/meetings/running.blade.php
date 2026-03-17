<!-- resources/views/meetings/running.blade.php -->
@extends('layouts.app')

@section('title', ($meeting->status === 'completed' ? 'Notulensi Rapat' : 'Meeting Berlangsung') . ' - ' . $meeting->title)

@section('hide_header', true)

@php
    // AMBIL DATA DEPARTMENTS LANGSUNG DI VIEW JIKA TIDAK ADA DI CONTROLLER
    if (!isset($departments)) {
        $departments = \App\Models\Department::active()->get();
    }
@endphp
@section('content')
<style>
    .card-premium {
        border: none !important;
        border-radius: 20px !important;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        transition: all 0.3s ease;
    }
    .card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08) !important;
    }
    .bg-emerald-soft { background-color: rgba(16, 185, 129, 0.1); }
    .text-emerald { color: #10b981; }
    .btn-emerald { background-color: #10b981; color: white; }
    .btn-emerald:hover { background-color: #059669; color: white; }
    .rounded-xl { border-radius: 16px !important; }
    .badge-soft-emerald { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-soft-success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-soft-warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-soft-danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .letter-spacing-1 { letter-spacing: 0.1em; }
    .text-xxs { font-size: 0.65rem; }
    .transition-all { transition: all 0.3s ease; }
    .hover-bg-light:hover { background-color: #f8fafc; }
    .auto-resize { min-height: 120px; resize: none; border-radius: 12px; border: 1px solid #e2e8f0; }
    .auto-resize:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
    
    /* Quill Editor Adjustments */
    .ql-container { border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; font-family: inherit; font-size: 0.95rem; }
    .ql-container.ql-snow { border-color: #e2e8f0 !important; min-height: 200px; background: #ffffff; }
    .ql-toolbar.ql-snow { border-color: #e2e8f0 !important; background: #f8fafc; border-top-left-radius: 12px; border-top-right-radius: 12px; }
    .editor-wrapper { position: relative; }
    .ql-editor { min-height: 200px; }
    .ql-editor.ql-blank::before { color: #94a3b8; font-style: normal; }
</style>

@push('styles')
<!-- Quill Assets -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <!-- Meeting Status -->
            <div class="card card-premium mb-4 overflow-hidden border-top-indigo">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-emerald-soft rounded-circle mb-3" style="width: 60px; height: 60px;">
                            <i class="fas {{ $meeting->status === 'completed' ? 'fa-check-circle' : 'fa-play-circle' }} fa-2x text-emerald"></i>
                        </div>
                        <h3 class="font-weight-bold text-dark mb-1">
                            Meeting {{ $meeting->status === 'completed' ? 'Selesai' : 'Sedang Berlangsung' }}
                        </h3>
                        <p class="text-muted mb-4">{{ $meeting->title }}</p>

                        <i class="fas {{ $meeting->status === 'completed' ? 'fa-file-alt' : 'fa-video' }} fa-3x {{ $meeting->status === 'completed' ? 'text-dark' : 'text-success' }} mb-2"></i>
                        <h5 class="{{ $meeting->status === 'completed' ? 'text-dark' : 'text-success' }} font-weight-bold">
                            Meeting "{{ $meeting->title }}" {{ $meeting->status === 'completed' ? 'Telah Selesai' : 'Sedang Berjalan' }}
                        </h5>
                        <p class="text-muted">
                            {{ $meeting->status === 'completed' ? 'Selesai pada: ' . ($meeting->ended_at ? $meeting->ended_at->format('d M Y H:i') : '-') : 'Mulai: ' . ($meeting->started_at ? $meeting->started_at->format('d M Y H:i') : '-') }}
                        </p>
                        
                        <!-- Info Minute Taker -->
                        @if($meeting->assignedMinuteTaker)
                        <div class="alert alert-info d-inline-flex align-items-center">
                            <i class="fas fa-user-edit mr-2"></i>
                            <strong>Penulis Notulensi:</strong> 
                            <span class="ml-2">{{ $meeting->assignedMinuteTaker->name }}</span>
                            @if($meeting->assigned_minute_taker_id == auth()->id())
                            <span class="badge badge-warning badge-pill ml-2">Anda</span>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Info Action Taker -->
                        @if($meeting->assignedActionTaker)
                        <div class="alert alert-success d-inline-flex align-items-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            <strong>Penulis Tindak Lanjut:</strong> 
                            <span class="ml-2">{{ $meeting->assignedActionTaker->name }}</span>
                            @if($meeting->assigned_action_taker_id == auth()->id())
                            <span class="badge badge-success badge-pill ml-2">Anda</span>
                            @endif
                        </div>
                        @endif
                        
                        <div class="row pt-4 border-top">
                            <div class="col-3">
                                <div class="h4 font-weight-bold text-dark mb-0">{{ $meeting->actionItems ? $meeting->actionItems->count() : 0 }}</div>
                                <div class="text-xxs text-muted font-weight-bold text-uppercase">Tugas</div>
                            </div>
                            <div class="col-3">
                                <div class="h4 font-weight-bold text-dark mb-0">{{ $meeting->files ? $meeting->files->count() : 0 }}</div>
                                <div class="text-xxs text-muted font-weight-bold text-uppercase">Berkas</div>
                            </div>
                            <div class="col-3">
                                <div class="h4 font-weight-bold text-dark mb-0">{{ $meeting->participants->count() }}</div>
                                <div class="text-xxs text-muted font-weight-bold text-uppercase">Peserta</div>
                            </div>
                            <div class="col-3">
                                <div class="h4 font-weight-bold text-dark mb-0">{{ $meeting->minutes ? 1 : 0 }}</div>
                                <div class="text-xxs text-muted font-weight-bold text-uppercase">Notulen</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Notulensi -->
            @if($meeting->assigned_minute_taker_id == auth()->id() || auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
            <div class="card card-premium mb-4" id="minuteTakerForm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-emerald-soft p-3 rounded-xl mr-3">
                            <i class="fas fa-edit text-emerald"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-dark mb-0">Form Notulensi</h5>
                            <span class="text-xs text-muted">Dokumentasikan poin-poin penting rapat.</span>
                        </div>
                    </div>
                    @if($meeting->minutes && $meeting->minutes->is_finalized)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong>Notulensi telah difinalisasi</strong> pada 
                        {{ $meeting->minutes->finalized_at->format('d M Y H:i') }}
                    </div>
                    @endif

                    <form action="{{ $meeting->minutes ? route('meetings.minutes.update', [$meeting, $meeting->minutes]) : route('meetings.minutes.store', $meeting) }}" method="POST">
                        @csrf
                        @if($meeting->minutes)
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label for="content" class="font-weight-bold">
                                <i class="fas fa-align-left mr-1"></i>Isi Notulensi *
                            </label>
                            <div class="editor-wrapper">
                                <div id="content-editor" style="height: 300px;">{!! old('content', $meeting->minutes->content ?? '') !!}</div>
                                <textarea name="content" id="content-hidden" style="display:none;">{{ old('content', $meeting->minutes->content ?? '') }}</textarea>
                            </div>
                            @error('content')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Jelaskan secara detail apa yang dibahas dalam meeting, keputusan yang diambil, dan hal penting lainnya.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="decisions" class="font-weight-bold">
                                <i class="fas fa-gavel mr-1"></i>Keputusan Meeting
                            </label>
                            <div class="editor-wrapper">
                                <div id="decisions-editor" style="height: 200px;">{!! old('decisions_html', $meeting->minutes ? implode("<br>", array_map(fn($d) => "- $d", $meeting->minutes->decisions ?? [])) : '') !!}</div>
                                <textarea name="decisions" id="decisions-hidden" style="display:none;">{{ old('decisions', $meeting->minutes ? implode("\n", $meeting->minutes->decisions ?? []) : '') }}</textarea>
                            </div>
                            @error('decisions')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Masukkan setiap keputusan yang diambil selama rapat berlangsung.
                            </small>
                        </div>

                        @if(!$meeting->minutes || !$meeting->minutes->is_finalized)
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_finalized" name="is_finalized" value="1"
                                       {{ old('is_finalized', $meeting->minutes->is_finalized ?? false) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold" for="is_finalized">
                                    <i class="fas fa-lock mr-1"></i>Finalisasi Notulensi
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Jika dicentang, notulensi tidak dapat diubah lagi. Pastikan semua informasi sudah benar sebelum memfinalisasi.
                            </small>
                        </div>
                        @endif

                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($meeting->minutes)
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Terakhir diperbarui: {{ $meeting->minutes->updated_at->format('d M Y H:i') }}
                                        </small>
                                    @endif
                                </div>
                                <div>
                                    @if($meeting->minutes && $meeting->minutes->is_finalized)
                                        <button type="button" class="btn btn-secondary" disabled>
                                            <i class="fas fa-lock mr-1"></i>Terkunci
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i>
                                            {{ $meeting->minutes ? 'Perbarui' : 'Simpan' }} Notulensi
                                        </button>
                                        
                                        @if($meeting->minutes)
                                        <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-secondary ml-2">
                                            <i class="fas fa-times mr-1"></i>Batal
                                        </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <!-- Info untuk user yang tidak memiliki akses edit -->
            <div class="card card-premium mb-4 shadow-sm border-0" id="minuteTakerForm">
                <div class="card-body p-5 text-center">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-file-signature text-muted fa-2x"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-2">Notulensi Meeting</h5>
                    @if($meeting->assignedMinuteTaker)
                        <p class="text-muted">Notulensi sedang dikerjakan oleh <strong>{{ $meeting->assignedMinuteTaker->name }}</strong>.</p>
                        <a href="#minutesPreview" class="btn btn-outline-primary rounded-pill px-4 mt-2">
                            <i class="fas fa-eye mr-2"></i>Lihat Preview
                        </a>
                    @else
                        <p class="text-muted">Belum ada penulis notulensi yang ditunjuk.</p>
                        @if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
                            <button type="button" class="btn btn-primary rounded-pill px-4 mt-2" data-toggle="modal" data-target="#assignMinuteTakerModal">
                                <i class="fas fa-user-plus mr-2"></i>Tunjuk Penulis
                            </button>
                        @endif
                    @endif
                </div>
            </div>
            @endif

            <!-- Tindak Lanjut Section -->
            <div class="card card-premium mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-emerald-soft p-3 rounded-xl mr-3">
                                <i class="fas fa-tasks text-emerald"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">Tindak Lanjut</h5>
                                <span class="text-xs text-muted">Daftar tugas dan penanggung jawab.</span>
                            </div>
                        </div>
                        @if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id() || $meeting->assigned_action_taker_id == auth()->id())
                        <button type="button" class="btn btn-emerald btn-sm px-3 rounded-lg font-weight-bold" data-toggle="modal" data-target="#addActionItemModal">
                            <i class="fas fa-plus mr-1 text-xs"></i> Tugas
                        </button>
                        @endif
                    </div>
@if($meeting->actionItems && $meeting->actionItems->count() > 0)
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 text-xxs font-weight-bold text-muted text-uppercase letter-spacing-1">Tugas</th>
                    <th class="border-0 text-xxs font-weight-bold text-muted text-uppercase letter-spacing-1">Penanggung Jawab</th>
                    <th class="border-0 text-xxs font-weight-bold text-muted text-uppercase letter-spacing-1">Batas Waktu</th>
                    <th class="border-0 text-xxs font-weight-bold text-muted text-uppercase letter-spacing-1">Status</th>
                    <th class="border-0 text-xxs font-weight-bold text-muted text-uppercase letter-spacing-1 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($meeting->actionItems as $actionItem)
                <tr>
                    <td>
                        <strong>{{ $actionItem->title }}</strong>
                        @if($actionItem->description)
                        <br><small class="text-muted">{{ Str::limit($actionItem->description, 50) }}</small>
                        @endif
                    </td>
                    <td>
                        {{ $actionItem->assignedTo->name ?? 'Tidak ada' }}
                        <br><small class="text-muted">{{ $actionItem->department->name ?? 'Tidak ada' }}</small>
                    </td>
                    <td>
                        {{ $actionItem->due_date->format('d M Y') }}
                        <br>
                        <small class="text-{{ $actionItem->due_date->isPast() ? 'danger' : 'muted' }}">
                            {{ $actionItem->due_date->diffForHumans() }}
                        </small>
                    </td>
                    <td>
                        @php
                            $statusBadge = [
                                'pending' => 'secondary',
                                'in_progress' => 'warning', 
                                'completed' => 'success',
                                'cancelled' => 'danger'
                            ];
                            $statusText = [
                                'pending' => 'Menunggu',
                                'in_progress' => 'Dalam Proses',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan'
                            ];
                        @endphp
                        <span class="badge badge-soft-{{ $statusBadge[$actionItem->status] ?? 'secondary' }} px-2 py-1">
                            {{ $statusText[$actionItem->status] ?? $actionItem->status }}
                        </span>
                    </td>
                    <td>
                        <!-- TAMBAHKAN TOMBOL HAPUS -->
                        <div class="d-flex align-items-center">
                            <a href="{{ route('action-items.show', $actionItem) }}" 
                               class="btn btn-info btn-sm mr-1" 
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @if(auth()->user()->canManageMeetings() || 
                                $meeting->organizer_id == auth()->id() || 
                                $actionItem->created_by == auth()->id())
                            <form action="{{ route('action-items.destroy', $actionItem) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirmDeleteActionItem(this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-danger btn-sm" 
                                        title="Hapus Tindak Lanjut">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-5">
        <div class="bg-light d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px;">
            <i class="fas fa-tasks text-muted fa-lg"></i>
        </div>
        <p class="text-muted mb-0">Belum ada tindak lanjut yang dicatat.</p>
    </div>
@endif
                    
                </div>
            </div>

            <!-- File Terupload Section -->
            <div class="card card-premium shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-emerald-soft p-3 rounded-xl mr-3">
                                <i class="fas fa-folder text-emerald"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">Berkas Terlampir</h5>
                                <span class="text-xs text-muted">Dokumen yang diunggah selama rapat.</span>
                            </div>
                        </div>
                        @if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
                        <button type="button" class="btn btn-emerald btn-sm px-3 rounded-lg font-weight-bold" data-toggle="modal" data-target="#uploadFileModal">
                            <i class="fas fa-upload mr-1 text-xs"></i> Unggah
                        </button>
                        @endif
                    </div>
                    @if($meeting->files && $meeting->files->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($meeting->files as $file)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center">
                                            @php
                                                $fileIcon = 'fa-file text-secondary';
                                                $fileColor = 'text-secondary';
                                                
                                                if (str_contains($file->file_type, 'pdf')) {
                                                    $fileIcon = 'fa-file-pdf text-danger';
                                                    $fileColor = 'text-danger';
                                                } elseif (str_contains($file->file_type, 'word') || str_contains($file->file_type, 'document')) {
                                                    $fileIcon = 'fa-file-word text-primary';
                                                    $fileColor = 'text-primary';
                                                } elseif (str_contains($file->file_type, 'excel') || str_contains($file->file_type, 'sheet')) {
                                                    $fileIcon = 'fa-file-excel text-success';
                                                    $fileColor = 'text-success';
                                                } elseif (str_contains($file->file_type, 'image')) {
                                                    $fileIcon = 'fa-file-image text-info';
                                                    $fileColor = 'text-info';
                                                } elseif (str_contains($file->file_type, 'powerpoint') || str_contains($file->file_type, 'presentation')) {
                                                    $fileIcon = 'fa-file-powerpoint text-warning';
                                                    $fileColor = 'text-warning';
                                                }
                                            @endphp
                                            <i class="fas {{ $fileIcon }} file-icon mr-3"></i>
                                            <div>
                                                <h6 class="mb-1 {{ $fileColor }}">{{ $file->file_name }}</h6>
                                                @if($file->description)
                                                <p class="text-muted small mb-1">{{ $file->description }}</p>
                                                @endif
                                                <small class="text-muted">
                                                    Diupload oleh: {{ $file->uploader->name ?? 'Unknown' }} • 
                                                    {{ $file->created_at->format('d M Y H:i') }} • 
                                                    {{ round($file->file_size / 1024) }} KB
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('meetings.files.preview', [$meeting, $file]) }}" target="_blank"
                                           class="btn btn-sm btn-outline-info" title="Lihat/Preview">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('meetings.files.download', [$meeting, $file]) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        
                                        @if($file->uploaded_by == auth()->id() || auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
                                        <form action="{{ route('meetings.files.delete', [$meeting, $file]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Hapus file ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-folder-open text-muted fa-lg"></i>
                            </div>
                            <p class="text-muted mb-0">Belum ada berkas yang diunggah.</p>
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Sidebar Column -->
            <div class="card card-premium mb-4">
                <div class="card-body p-4">
                    <h6 class="text-xs text-uppercase text-muted font-weight-bold mb-3 letter-spacing-1">Aksi Cepat</h6>
                    <div class="d-grid gap-2">
                        @if($meeting->status === 'ongoing')
                        <form action="{{ route('meetings.complete', $meeting) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-soft-danger btn-block text-left py-2 px-3 rounded-lg font-weight-bold transition-all" 
                                    onclick="return confirm('Selesaikan meeting?')">
                                <i class="fas fa-stop mr-2 opacity-50"></i>Akhiri Meeting
                            </button>
                        </form>
                        @endif

                        @php
                            $isOrganizer = $meeting->organizer_id == auth()->id();
                            $isAdmin = auth()->user()->isAdmin();
                            $isParticipant = $meeting->participants->where('user_id', auth()->id())->first();
                        @endphp

                        @if($isOrganizer || $isAdmin)
                        <button type="button" class="btn btn-emerald btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold shadow-sm transition-all" data-toggle="modal" data-target="#attendanceModal">
                            <i class="fas fa-list-check mr-2"></i>Daftar Kehadiran
                        </button>
                        @elseif($isParticipant)
                        <button type="button" class="btn btn-emerald btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold shadow-sm transition-all" data-toggle="modal" data-target="#selfAttendanceModal">
                            <i class="fas fa-user-check mr-2"></i>Isi Kehadiran
                        </button>
                        @endif

                        @php
                            $canManage = auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id();
                            $isMinuteTaker = $meeting->assigned_minute_taker_id == auth()->id();
                        @endphp

                        @if($canManage || $isMinuteTaker)
                        <a href="#minuteTakerForm" class="btn btn-soft-primary btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold transition-all">
                            <i class="fas fa-edit mr-2 opacity-50"></i>Tulis Notulensi
                        </a>
                        @endif

                        @if($canManage)
                        <button type="button" class="btn btn-light btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold text-emerald transition-all" data-toggle="modal" data-target="#assignMinuteTakerModal">
                            <i class="fas fa-user-edit mr-2 opacity-50"></i>@if($meeting->assignedMinuteTaker) Ganti @endif Notulis
                        </button>
                        
                        <button type="button" class="btn btn-light btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold text-emerald transition-all" data-toggle="modal" data-target="#assignActionTakerModal">
                            <i class="fas fa-user-plus mr-2 opacity-50"></i>@if($meeting->assignedActionTaker) Ganti @endif Action Taker
                        </button>
                        @endif
                        
                        @if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id() || $meeting->assigned_action_taker_id == auth()->id())
                        <button type="button" class="btn btn-light btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold text-emerald transition-all" data-toggle="modal" data-target="#addActionItemModal">
                            <i class="fas fa-plus-circle mr-2 opacity-50"></i>Tambah Tugas
                        </button>
                        @endif
                        
                        <button type="button" class="btn btn-light btn-block text-left mb-2 py-2 px-3 rounded-lg font-weight-bold text-emerald transition-all" data-toggle="modal" data-target="#uploadFileModal">
                            <i class="fas fa-upload mr-2 opacity-50"></i>Unggah Berkas
                        </button>

                        <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-light btn-block text-left py-2 px-3 rounded-lg font-weight-bold text-emerald transition-all">
                            <i class="fas fa-eye mr-2 opacity-50"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>

            <!-- Preview Notulensi -->
            <div class="card card-premium mb-4" id="minutesPreview">
                <div class="card-body p-4">
                    <h6 class="text-xs text-uppercase text-muted font-weight-bold mb-3 letter-spacing-1">Preview Notulensi</h6>
                    @if($meeting->minutes)
                        <div class="minutes-content mb-3 minutes-preview">
                            <h6>Isi Notulensi:</h6>
                            <div class="border rounded p-3 bg-light small ql-editor">
                                {!! html_entity_decode($meeting->minutes->content) ?: '<em class="text-muted">Belum ada isi notulensi.</em>' !!}
                            </div>
                            
                            <!-- Handle decisions -->
                            @if($meeting->minutes->decisions && count($meeting->minutes->decisions) > 0)
                            <h6 class="mt-3">Keputusan:</h6>
                            <ul class="list-group small">
                                @foreach($meeting->minutes->decisions as $decision)
                                    @if(!empty(trim($decision)))
                                    <li class="list-group-item py-2 ql-editor">
                                        {!! html_entity_decode($decision) !!}
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                            @endif
                        </div>
                        
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                @if($meeting->minutes->is_finalized)
                                <span class="badge badge-success">
                                    <i class="fas fa-check mr-1"></i> Telah Difinalisasi
                                </span>
                                @else
                                <span class="badge badge-warning">
                                    <i class="fas fa-pencil-alt mr-1"></i> Draft
                                </span>
                                @endif
                                
                                <small class="text-muted">
                                    Oleh: {{ $meeting->minutes->minuteTaker->name ?? 'Unknown' }}
                                </small>
                            </div>
                            @if($meeting->minutes->finalized_at)
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-calendar mr-1"></i>
                                Finalisasi: {{ $meeting->minutes->finalized_at->format('d M Y H:i') }}
                            </small>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-clipboard fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Belum ada notulensi</p>
                            
                            @if(!$meeting->assignedMinuteTaker)
                            <small class="text-muted">
                                Menunggu penunjukan penulis notulensi
                            </small>
                            @else
                            <small class="text-muted">
                                Menunggu {{ $meeting->assignedMinuteTaker->name }} membuat notulensi
                            </small>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Participants -->
            <div class="card card-premium mb-4">
                <div class="card-body p-4">
                    <h6 class="text-xs text-uppercase text-muted font-weight-bold mb-3 letter-spacing-1">Peserta Meeting</h6>
                    <div class="list-group list-group-flush">
                        @foreach($meeting->participants as $participant)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="mr-3 position-relative">
                                    <i class="fas fa-user-circle fa-lg 
                                        {{ $participant->role === 'chairperson' ? 'text-success' : 'text-muted' }}"></i>
                                    @if($participant->user_id == $meeting->assigned_minute_taker_id)
                                    <span class="minute-taker-badge" title="Penulis Notulensi"></span>
                                    @endif
                                    @if($participant->user_id == $meeting->assigned_action_taker_id)
                                    <span class="action-taker-badge" title="Penulis Tindak Lanjut"></span>
                                    @endif
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <div class="d-flex align-items-center mb-0">
                                        <strong class="text-dark text-truncate mr-2">{{ $participant->user->name }}</strong>
                                        <div class="d-flex flex-wrap align-items-center">
                                            @if($participant->attended === true)
                                                <span class="badge badge-soft-success text-xxs mr-1" title="Hadir"><i class="fas fa-check mr-1"></i>Hadir</span>
                                            @elseif($participant->attended === false && $participant->excuse)
                                                <span class="badge badge-soft-warning text-xxs mr-1" title="Izin: {{ $participant->excuse }}"><i class="fas fa-info-circle mr-1"></i>Izin</span>
                                            @elseif($participant->attended === false)
                                                <span class="badge badge-soft-danger text-xxs mr-1" title="Tidak Hadir"><i class="fas fa-times mr-1"></i>Alfa</span>
                                            @endif

                                            @if($participant->user_id == $meeting->assigned_minute_taker_id)
                                                <span class="badge badge-warning text-xxs mr-1" title="Penulis Notulensi">Notulis</span>
                                            @endif
                                            @if($participant->user_id == $meeting->assigned_action_taker_id)
                                                <span class="badge badge-success text-xxs mr-1" title="Penulis Tindak Lanjut">Action</span>
                                            @endif
                                            <span class="badge badge-soft-secondary text-xxs">
                                                {{ $participant->role === 'chairperson' ? 'Ketua' : 'Peserta' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-xxs text-muted text-truncate">
                                        {{ $participant->user->position ?? '-' }} • {{ $participant->user->department->name ?? '-' }}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center flex-shrink-0 ml-auto pl-3">
                                    @if($participant->score)
                                    <div class="text-center px-1 py-1 bg-light rounded mr-2" style="border: 1px solid #f1f5f9; min-width: 40px;">
                                        <div class="text-emerald font-weight-bold" style="font-size: 0.85rem; line-height: 1;">{{ $participant->score }}</div>
                                        <div class="text-muted" style="font-size: 0.55rem; font-weight: 800; text-transform: uppercase;">Skor</div>
                                    </div>
                                    @endif

                                    <div class="d-flex">
                                        @if($participant->score_note)
                                        <button type="button" class="btn btn-xs btn-soft-info mr-1 py-0 px-2 rounded-pill font-weight-bold" 
                                                style="font-size: 0.65rem;"
                                                data-toggle="popover" 
                                                data-trigger="focus" 
                                                data-placement="auto"
                                                title="Catatan Evaluasi" 
                                                data-content="{{ $participant->score_note }}">
                                            <i class="fas fa-comment-alt mr-1"></i> Catatan
                                        </button>
                                        @endif

                                        @if($meeting->status === 'completed' && ($meeting->organizer_id == auth()->id() || auth()->user()->canManageMeetings()))
                                        <button type="button" class="btn btn-xs btn-soft-primary py-0 px-2 rounded-pill font-weight-bold" 
                                                style="font-size: 0.65rem;"
                                                data-toggle="modal" data-target="#rateModal-{{ $participant->id }}">
                                            <i class="fas fa-star mr-1"></i> Nilai
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Meeting Info -->
            <div class="card card-premium">
                <div class="card-body p-4">
                    <h6 class="text-xs text-uppercase text-muted font-weight-bold mb-3 letter-spacing-1">Informasi Meeting</h6>
                    <div class="mb-3">
                        <strong><i class="fas fa-calendar mr-2 text-primary"></i>Jadwal:</strong><br>
                        <span class="ml-4">{{ $meeting->start_time->format('d M Y H:i') }} - {{ $meeting->end_time->format('H:i') }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong><i class="fas fa-map-marker-alt mr-2 text-primary"></i>Lokasi:</strong><br>
                        <span class="ml-4">
                            @if($meeting->is_online)
                                <i class="fas fa-video text-info mr-1"></i>Online
                                @if($meeting->meeting_link)
                                <br><a href="{{ $meeting->meeting_link }}" target="_blank" class="small">Join Meeting</a>
                                @endif
                            @else
                                <i class="fas fa-building text-secondary mr-1"></i>{{ $meeting->location }}
                            @endif
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong><i class="fas fa-tag mr-2 text-primary"></i>Jenis Meeting:</strong><br>
                        <span class="ml-4">{{ $meeting->meetingType->name }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong><i class="fas fa-building mr-2 text-primary"></i>Departemen:</strong><br>
                        <span class="ml-4">{{ $meeting->department->name }}</span>
                    </div>

                    <div>
                        <strong><i class="fas fa-user-tie mr-2 text-primary"></i>Organizer:</strong><br>
                        <span class="ml-4">{{ $meeting->organizer->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Di Modal Add Action Item - PERBAIKAN -->
<div class="modal fade" id="addActionItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title m-0">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Tindak Lanjut
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('meetings.action-items.store', $meeting) }}" method="POST" id="addActionItemForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title" class="font-weight-bold small">Judul Tindak Lanjut *</label>
                                <input type="text" class="form-control form-control-sm" id="title" name="title" 
                                       placeholder="Masukkan judul tindak lanjut" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="font-weight-bold small">Deskripsi *</label>
                        <textarea class="form-control form-control-sm" id="description" name="description" rows="3" 
                                  placeholder="Jelaskan detail tindak lanjut yang harus dilakukan" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assigned_to" class="font-weight-bold small">Ditugaskan ke *</label>
                                <select class="form-control form-control-sm select2" id="assigned_to" name="assigned_to" required>
                                    <option value="">Pilih User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }} - {{ $user->position ?? 'No Position' }} ({{ $user->department->name ?? 'No Department' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department_id" class="font-weight-bold small">Departemen *</label>
                                <select class="form-control form-control-sm" id="department_id" name="department_id" required>
                                    <option value="">Pilih Departemen</option>
                                    @if($departments->count() > 0)
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    @else
                                        <!-- Fallback jika departments tidak ada -->
                                        <option value="{{ $meeting->department_id }}">{{ $meeting->department->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="due_date" class="font-weight-bold small">Batas Waktu *</label>
                                <input type="date" class="form-control form-control-sm" id="due_date" name="due_date" 
                                       min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority" class="font-weight-bold small">Prioritas *</label>
                                <select class="form-control form-control-sm" id="priority" name="priority" required>
                                    <!-- PERBAIKAN: Urutan sesuai yang user lihat -->
                                    <option value="1">🔴 Tinggi</option>
                                    <option value="2" selected>🟡 Sedang</option>
                                    <option value="3">🟢 Rendah</option>
                                </select>
                                <small class="form-text text-muted">
                                    🔴 Tinggi = Sangat penting & mendesak<br>
                                    🟡 Sedang = Penting tapi tidak mendesak<br>
                                    🟢 Rendah = Biasa, bisa dikerjakan belakangan
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload File Modal -->
<div class="modal fade" id="uploadFileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title m-0">
                    <i class="fas fa-upload mr-2"></i>Upload File Meeting
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('meetings.files.upload', $meeting) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file" class="font-weight-bold small">File *</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file" name="file" required>
                            <label class="custom-file-label" for="file">Pilih file...</label>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>Maksimal 10MB. Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="description" class="font-weight-bold small">Deskripsi File</label>
                        <textarea class="form-control form-control-sm" id="description" name="description" rows="2" 
                                  placeholder="Deskripsi singkat tentang file ini"></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-info btn-sm">
                        <i class="fas fa-upload mr-1"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Minute Taker Modal -->
<div class="modal fade" id="assignMinuteTakerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white py-3">
                <h5 class="modal-title m-0">
                    <i class="fas fa-user-edit mr-2"></i>Tunjuk Penulis Notulensi
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('meetings.assign-minute-taker', $meeting) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="minute_taker_id" class="font-weight-bold small">Pilih Penulis Notulensi *</label>
                        <select class="form-control form-control-sm select2" id="minute_taker_id" name="minute_taker_id" required>
                            <option value="">Pilih User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ $meeting->assigned_minute_taker_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ $user->position ?? 'No Position' }} ({{ $user->department->name ?? 'No Department' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Penulis notulensi yang ditunjuk akan memiliki akses untuk membuat dan mengedit notulensi meeting ini.
                        </small>
                    </div>
                    
                    @if($meeting->assignedMinuteTaker)
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle mr-1"></i>
                        Saat ini: <strong>{{ $meeting->assignedMinuteTaker->name }}</strong>
                    </div>
                    @endif
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Action Taker Modal -->
<div class="modal fade" id="assignActionTakerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title m-0">
                    <i class="fas fa-user-plus mr-2"></i>Tunjuk Penulis Tindak Lanjut
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('meetings.assign-action-taker', $meeting) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="action_taker_id" class="font-weight-bold small">Pilih Penulis Tindak Lanjut *</label>
                        <select class="form-control form-control-sm select2" id="action_taker_id" name="action_taker_id" required>
                            <option value="">Pilih User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ $meeting->assigned_action_taker_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ $user->position ?? 'No Position' }} ({{ $user->department->name ?? 'No Department' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            User yang ditunjuk akan memiliki akses untuk menambah tindak lanjut selama meeting berlangsung.
                        </small>
                    </div>
                    
                    @if($meeting->assignedActionTaker)
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle mr-1"></i>
                        Saat ini: <strong>{{ $meeting->assignedActionTaker->name }}</strong>
                    </div>
                    @endif
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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
    border-color: #10b981 ! from-emerald-500;
    box-shadow: 0 0 0 2px #10b981;
}
.attendance-box:hover {
    background-color: #f8fafc;
}
.cursor-pointer { cursor: pointer; }
.rounded-xl { border-radius: 12px; }
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirm meeting completion
    const meetingCompleteForm = document.querySelector('form[action*="meetings.complete"]');
    if (meetingCompleteForm) {
        meetingCompleteForm.addEventListener('submit', function(e) {
            if (!confirm('Akhiri meeting ini? Tindakan ini tidak dapat dibatalkan.')) {
                e.preventDefault();
            }
        });
    }
    
    // Handle form submissions dengan feedback
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';
                button.disabled = true;
                
                // Re-enable button setelah 5 detik untuk menghindari stuck
                setTimeout(function() {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 5000);
            }
        });
    });

    // Initialize Select2 jika ada
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2({
            placeholder: 'Pilih user',
            allowClear: true,
            width: '100%'
        });
    }

    // Auto-resize textarea
    const textareas = document.querySelectorAll('.auto-resize');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        // Trigger initial resize
        textarea.dispatchEvent(new Event('input'));
    });

    // Custom file input
    document.querySelector('.custom-file-input')?.addEventListener('change', function(e) {
        var fileName = document.getElementById("file").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // Smooth scroll untuk anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Handle form tambah tindak lanjut - VALIDASI PRIORITAS
const addActionForm = document.getElementById('addActionItemForm');
if (addActionForm) {
    addActionForm.addEventListener('submit', function(e) {
        const priority = document.getElementById('priority').value;
        const dueDate = document.getElementById('due_date').value;
        const title = document.getElementById('title').value;
        
        // Validasi due date tidak boleh hari kemarin
        if (dueDate && new Date(dueDate) < new Date().setHours(0,0,0,0)) {
            e.preventDefault();
            alert('❌ Batas waktu tidak boleh hari kemarin atau sebelumnya');
            return false;
        }
        
        // Validasi title tidak boleh kosong
        if (!title.trim()) {
            e.preventDefault();
            alert('❌ Judul tindak lanjut harus diisi');
            return false;
        }
        
        // Konfirmasi berdasarkan prioritas - SESUAI DENGAN NILAI YANG BARU
        if (priority === '1') { // Tinggi
            if (!confirm('🚨 ANDA MEMILIH PRIORITAS TINGGI!\n\nTindak lanjut ini akan ditandai sebagai sangat penting dan mendesak.\nPastikan ini benar-benar tugas yang kritis.\n\nLanjutkan?')) {
                e.preventDefault();
                return false;
            }
        } else if (priority === '2') { // Sedang
            if (!confirm('🟡 Anda memilih prioritas SEDANG.\n\nTindak lanjut ini penting tapi tidak mendesak.\n\nLanjutkan?')) {
                e.preventDefault();
                return false;
            }
        } else if (priority === '3') { // Rendah
            if (!confirm('🟢 Anda memilih prioritas RENDAH.\n\nTindak lanjut ini bersifat biasa dan bisa dikerjakan belakangan.\n\nLanjutkan?')) {
                e.preventDefault();
                return false;
            }
        }
    });
}



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

    // Initialize Popovers
    $('[data-toggle="popover"]').popover();
});

    
    // Auto-set department berdasarkan user yang dipilih
    const assignedToSelect = document.getElementById('assigned_to');
    const departmentSelect = document.getElementById('department_id');
    
    if (assignedToSelect && departmentSelect) {
        // Buat mapping user ke department
        const userDepartments = {
            @foreach($participants as $participant)
            '{{ $participant->id }}': '{{ $participant->department_id }}',
            @endforeach
        };
        
        assignedToSelect.addEventListener('change', function() {
            const userId = this.value;
            const departmentId = userDepartments[userId];
            
            if (departmentId && departmentId !== '') {
                departmentSelect.value = departmentId;
                
                // Jika department tidak ada di options, tambahkan
                if (!departmentSelect.querySelector(`option[value="${departmentId}"]`)) {
                    const user = {!! $participants->firstWhere('id', '==', ' + userId + ') ? json_encode($participants->firstWhere('id', '==', userId)) : 'null' !!};
                    if (user && user.department) {
                        const option = new Option(user.department.name, user.department.id, true, true);
                        departmentSelect.appendChild(option);
                        departmentSelect.value = user.department.id;
                    }
                }
            }
        });
    }

    // Set minimum date untuk due_date ke hari ini
    const dueDateInput = document.getElementById('due_date');
    if (dueDateInput) {
        const today = new Date().toISOString().split('T')[0];
        dueDateInput.min = today;
        
        // Set default value ke 7 hari dari sekarang
        const nextWeek = new Date();
        nextWeek.setDate(nextWeek.getDate() + 7);
        const nextWeekFormatted = nextWeek.toISOString().split('T')[0];
        dueDateInput.value = nextWeekFormatted;
    }
});

// Simple toast notification
function showToast(message, type = 'info') {
    // Hapus toast sebelumnya jika ada
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show custom-toast position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <strong>${type === 'success' ? '✅ Sukses!' : type === 'danger' ? '❌ Error!' : 'ℹ️ Info!'}</strong> ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    document.body.appendChild(toast);
    
    // Auto-hide setelah 3 detik
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}

// Di section scripts - TAMBAHKAN FUNGSI KONFIRMASI HAPUS
function confirmDeleteActionItem(form) {
    const actionItemTitle = form.closest('tr').querySelector('td strong').textContent;
    
    if (!confirm(`Hapus tindak lanjut "${actionItemTitle.trim()}"?\n\nTindakan ini tidak dapat dibatalkan!`)) {
        return false;
    }
    
    // Tampilkan loading
    const button = form.querySelector('button[type="submit"]');
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    return true;
}

// Fungsi untuk hapus dengan AJAX (opsional)
function deleteActionItem(actionItemId, actionItemTitle) {
    if (!confirm(`Hapus tindak lanjut "${actionItemTitle}"?\n\nTindakan ini tidak dapat dibatalkan!`)) {
        return;
    }
    
    // Tampilkan loading
    const button = event.target;
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    // AJAX delete
    fetch(`/action-items/${actionItemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (response.ok) {
            // Reload halaman setelah sukses
            window.location.reload();
        } else {
            throw new Error('Gagal menghapus tindak lanjut');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus tindak lanjut');
        button.innerHTML = originalHtml;
        button.disabled = false;
    });
}

// Handle AJAX errors globally
window.addEventListener('unhandledrejection', function(event) {
    console.error('Unhandled promise rejection:', event.reason);
    showToast('Terjadi kesalahan sistem', 'danger');
});
</script>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showToast('{{ session('success') }}', 'success');
});
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof showToast === 'function') {
            showToast('{{ session('error') }}', 'danger');
        }
    });
</script>
@endif

@push('scripts')
<!-- Quill Library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Meeting Running Page Loaded');

    // --- Auto-scroll logic ---
    if (window.location.hash) {
        const targetElement = document.querySelector(window.location.hash);
        if (targetElement) {
            setTimeout(() => {
                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                targetElement.classList.add('border-primary', 'shadow');
                setTimeout(() => {
                    targetElement.classList.remove('border-primary', 'shadow');
                }, 2000);
            }, 300);
        }
    }

    // --- Quill Editor Initialization ---
    // Safely check for elements before initializing
    const contentContainer = document.querySelector('#content-editor');
    const decisionsContainer = document.querySelector('#decisions-editor');

    if (contentContainer && decisionsContainer && typeof Quill !== 'undefined') {
        const isFinalized = @json($meeting->minutes?->is_finalized ?? false);
        
        const toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike', 'link'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'align': [] }],
            [{ 'header': [1, 2, 3, false] }],
            ['clean']
        ];

        // Initialize Content Editor
        const contentEditor = new Quill('#content-editor', {
            modules: { toolbar: isFinalized ? false : toolbarOptions },
            theme: 'snow',
            placeholder: 'Tuliskan rangkuman dan poin-poin penting dari meeting ini...',
            readOnly: isFinalized
        });

        // Initialize Decisions Editor
        const decisionsEditor = new Quill('#decisions-editor', {
            modules: { toolbar: isFinalized ? false : toolbarOptions },
            theme: 'snow',
            placeholder: 'Tuliskan setiap keputusan yang diambil selama rapat berlangsung...',
            readOnly: isFinalized
        });

        // Sync Quill content to hidden textareas on form submission
        const minuteForm = document.querySelector('#minuteTakerForm form');
        if (minuteForm && !isFinalized) {
            minuteForm.addEventListener('submit', function(e) {
                const contentHtml = contentEditor.root.innerHTML;
                const decisionsHtml = decisionsEditor.root.innerHTML;

                // Strip HTML to check if really empty
                const contentText = contentEditor.getText().trim();
                if (contentText.length === 0) {
                    e.preventDefault();
                    alert('Isi notulensi tidak boleh kosong.');
                    return;
                }

                document.querySelector('#content-hidden').value = contentHtml;
                document.querySelector('#decisions-hidden').value = decisionsHtml;
            });
        }
        
        console.log('Quill successfully initialized.');
    } else {
        console.warn('Quill or editor containers not found.');
    }
});
</script>
@endpush

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
@endsection