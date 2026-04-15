@extends('layouts.app')

@section('title', 'Detail Tindak Lanjut - ' . $actionItem->title)

@section('hide_header', true)

@section('content')
<div class="row">
    <div class="col-md-8">
        
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3">
                <h3 class="card-title m-0">
                    <i class="fas fa-tasks mr-2 text-primary"></i>Detail Tindak Lanjut
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box bg-white mb-3">
                            <span class="info-box-icon bg-primary"><i class="fas fa-heading text-white"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Judul Tindak Lanjut</span>
                                <span class="info-box-number">{{ $actionItem->title }}</span>
                            </div>
                        </div>
                        
                        <div class="info-box bg-white mb-3">
                            <span class="info-box-icon bg-info"><i class="fas fa-calendar text-white"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Batas Waktu</span>
                                <span class="info-box-number">
                                    <span class="badge badge-{{ $actionItem->isOverdue() ? 'danger' : 'secondary' }}">
                                        {{ $actionItem->due_date->format('d F Y') }}
                                    </span>
                                    @if($actionItem->isOverdue())
                                    <br>
                                    <small class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        Terlambat {{ $actionItem->due_date->diffInDays(now()) }} hari
                                    </small>
                                    @elseif($actionItem->isCompletedLate())
                                    <br>
                                    <small class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        Selesai Terlambat {{ $actionItem->due_date->startOfDay()->diffInDays($actionItem->completed_at->startOfDay()) }} hari
                                    </small>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-box bg-white mb-3">
                            <span class="info-box-icon bg-warning"><i class="fas fa-user-tie text-white"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Ditugaskan ke</span>
                                <span class="info-box-number">{{ $actionItem->assignedTo->name }}</span>
                                <small class="text-muted">{{ $actionItem->assignedTo->position }}</small>
                            </div>
                        </div>
                        
                        <div class="info-box bg-white mb-3">
                            <span class="info-box-icon bg-success"><i class="fas fa-building text-white"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Departemen</span>
                                <span class="info-box-number">{{ $actionItem->department->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="callout callout-{{ $actionItem->status === 'completed' ? 'success' : ($actionItem->status === 'needs_revision' ? 'danger' : ($actionItem->status === 'in_progress' ? 'info' : ($actionItem->status === 'waiting_review' ? 'warning' : 'secondary'))) }}">
                            <h6 class="mb-1"><i class="fas fa-flag mr-2"></i>Status</h6>
                            <span class="badge badge-{{ $actionItem->status_badge }}">
                                {{ $actionItem->status_label }}
                            </span>
                            @if($actionItem->completed_at && $actionItem->status === 'completed')
                            <br>
                            <small class="text-muted">
                                Diselesaikan: {{ $actionItem->completed_at->format('d M Y H:i') }}
                            </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="callout callout-{{ $actionItem->priority === 3 ? 'danger' : ($actionItem->priority === 2 ? 'warning' : 'success') }}">
                            <h6 class="mb-1"><i class="fas fa-exclamation-circle mr-2"></i>Prioritas</h6>
                            <span class="badge badge-{{ $actionItem->priority_badge }}">
                                {{ $actionItem->priority_label }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($actionItem->revision_notes && $actionItem->status == 'needs_revision')
                <div class="callout callout-danger shadow-sm mb-4">
                    <h6 class="font-weight-bold mb-2 text-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Tugas Dikembalikan (Perlu Revisi)</h6>
                    <p class="mb-0 text-sm">Penyelenggara menolak laporan Anda dengan catatan berikut:</p>
                    <hr class="border-danger my-2">
                    <div class="bg-white text-dark p-2 rounded text-sm">
                        {{ $actionItem->revision_notes }}
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <h6 class="text-primary mb-2"><i class="fas fa-align-left mr-2"></i>Deskripsi</h6>
                    <div class="border rounded p-3 bg-light">
                        {{ $actionItem->description }}
                    </div>
                </div>

                @if($actionItem->completion_notes)
                <div class="mb-3">
                    <h6 class="text-success mb-2"><i class="fas fa-check-circle mr-2"></i>Catatan Penyelesaian</h6>
                    <div class="border rounded p-3 bg-light">
                        {{ $actionItem->completion_notes }}
                    </div>
                </div>
                @endif

                @if($actionItem->meeting)
                <div class="mb-3">
                    <h6 class="text-primary mb-2"><i class="fas fa-users mr-2"></i>Meeting Asal</h6>
                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $actionItem->meeting->title }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $actionItem->meeting->start_time->format('d F Y H:i') }} - 
                                    {{ $actionItem->meeting->end_time->format('H:i') }}
                                </small>
                            </div>
                            <a href="{{ route('meetings.show', $actionItem->meeting) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye mr-1"></i> Lihat Meeting
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Meeting asal telah dihapus.</strong> Tindak lanjut ini tetap tersedia untuk tracking.
                </div>
                @endif
            </div>
            
            <div class="card-footer">
                <div>
                    <a href="{{ route('action-items.index') }}" class="btn btn-secondary btn-sm mr-2 mb-1">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                    </a>
                    
                    @if(auth()->user()->isAdmin() || (isset($actionItem->meeting) && $actionItem->meeting->organizer_id == auth()->id()))
                    <a href="{{ route('action-items.edit', $actionItem) }}" class="btn btn-primary btn-sm mr-2 mb-1">
                        <i class="fas fa-edit mr-1"></i> Edit Detail
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h3 class="card-title m-0">
                    <i class="fas fa-file mr-2 text-primary"></i>File Lampiran
                    <span class="badge badge-info badge-pill ml-1">{{ $actionItem->files->count() }}</span>
                </h3>
                @if($actionItem->assigned_to == auth()->id() && in_array($actionItem->status, ['pending', 'in_progress', 'needs_revision']))
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#uploadFileModal">
                        <i class="fas fa-upload mr-1"></i> Upload Bukti
                    </button>
                </div>
                @endif
            </div>
            <div class="card-body p-0">
                @if($actionItem->files->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($actionItem->files as $file)
                    @php
                        $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                        $iconMap = [
                            'pdf' => ['fa-file-pdf', 'text-danger'],
                            'doc' => ['fa-file-word', 'text-primary'],
                            'docx' => ['fa-file-word', 'text-primary'],
                            'xls' => ['fa-file-excel', 'text-success'],
                            'xlsx' => ['fa-file-excel', 'text-success'],
                            'ppt' => ['fa-file-powerpoint', 'text-warning'],
                            'pptx' => ['fa-file-powerpoint', 'text-warning'],
                            'jpg' => ['fa-file-image', 'text-info'],
                            'jpeg' => ['fa-file-image', 'text-info'],
                            'png' => ['fa-file-image', 'text-info'],
                            'gif' => ['fa-file-image', 'text-info'],
                            'zip' => ['fa-file-archive', 'text-secondary'],
                            'rar' => ['fa-file-archive', 'text-secondary'],
                        ];
                        $icon = $iconMap[$ext] ?? ['fa-file', 'text-muted'];
                    @endphp
                    <div class="list-group-item file-attachment-item py-3">
                        <div class="d-flex align-items-start">
                            {{-- File Icon --}}
                            <div class="file-icon-wrapper mr-3 flex-shrink-0">
                                <div class="file-icon-box rounded d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #f0f4ff;">
                                    <i class="fas {{ $icon[0] }} {{ $icon[1] }} fa-lg"></i>
                                </div>
                            </div>

                            {{-- File Info --}}
                            <div class="flex-grow-1" style="min-width: 0;">
                                {{-- Filename --}}
                                <div class="d-flex align-items-center mb-1">
                                    <strong class="text-truncate d-block" title="{{ $file->file_name }}" style="max-width: 100%;">
                                        {{ $file->file_name }}
                                    </strong>
                                </div>

                                {{-- Metadata row --}}
                                <div class="d-flex flex-wrap align-items-center text-muted" style="gap: 4px 12px; font-size: 0.8rem;">
                                    <span title="Ukuran file">
                                        <i class="fas fa-hdd mr-1"></i>{{ $file->file_size_formatted }}
                                    </span>
                                    <span class="d-none d-sm-inline text-light">|</span>
                                    <span title="Diupload oleh">
                                        <i class="fas fa-user mr-1"></i>{{ $file->uploader->name }}
                                    </span>
                                    <span class="d-none d-sm-inline text-light">|</span>
                                    <span title="Tanggal upload">
                                        <i class="fas fa-clock mr-1"></i>{{ $file->created_at->format('d M Y H:i') }}
                                    </span>
                                </div>

                                {{-- Description --}}
                                @if($file->description)
                                <div class="file-description mt-2 p-2 rounded" style="background-color: #f8f9fb; border-left: 3px solid #4e73df; font-size: 0.82rem;">
                                    <i class="fas fa-comment-alt mr-1 text-primary" style="font-size: 0.75rem;"></i>
                                    <span class="text-dark">{{ $file->description }}</span>
                                </div>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="ml-3 flex-shrink-0 d-flex align-items-center" style="gap: 4px;">
                                <button type="button" class="btn btn-outline-info btn-sm file-preview-btn"
                                    data-url="{{ route('action-items.preview-file', [$actionItem, $file]) }}"
                                    data-name="{{ $file->file_name }}"
                                    data-type="{{ $file->file_type }}"
                                    data-size="{{ $file->file_size_formatted }}"
                                    data-download="{{ route('action-items.download-file', [$actionItem, $file]) }}"
                                    title="Lihat/Preview">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('action-items.download-file', [$actionItem, $file]) }}" class="btn btn-outline-success btn-sm" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                @if($file->uploaded_by == auth()->id() && in_array($actionItem->status, ['pending', 'in_progress', 'needs_revision']))
                                <form action="{{ route('action-items.delete-file', [$actionItem, $file]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus file ini?')" title="Hapus">
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
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3 d-block" style="opacity: 0.3;"></i>
                    <p class="text-muted mb-0">Belum ada file bukti yang diunggah.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3">
                <h3 class="card-title m-0">
                    <i class="fas fa-info-circle mr-2 text-primary"></i>Informasi
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">
                        <i class="fas fa-user-plus mr-2"></i>
                        <strong>Dibuat oleh:</strong> 
                        {{ $actionItem->meeting->organizer->name ?? 'Unknown' }}
                    </small>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        <strong>Dibuat pada:</strong> 
                        {{ $actionItem->created_at->format('d M Y H:i') }}
                    </small>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">
                        <i class="fas fa-calendar-check mr-2"></i>
                        <strong>Diupdate pada:</strong> 
                        {{ $actionItem->updated_at->format('d M Y H:i') }}
                    </small>
                </div>
                
                @if($actionItem->assigned_to == auth()->id() || (isset($actionItem->meeting) && auth()->id() == $actionItem->meeting->organizer_id) || auth()->user()->isAdmin())
                <hr>
                <div class="mt-3">
                    <h6 class="text-primary mb-3"><i class="fas fa-tasks mr-1"></i> Aksi Tindak Lanjut</h6>

                    @if($actionItem->status == 'completed')
                        <div class="alert alert-success py-2 m-0"><i class="fas fa-check-circle mr-1"></i> Tugas telah diverifikasi & selesai.</div>
                    @elseif($actionItem->status == 'cancelled')
                        <div class="alert alert-danger py-2 m-0"><i class="fas fa-times-circle mr-1"></i> Tugas dibatalkan.</div>
                    @else
                        
                        @if(auth()->id() == $actionItem->assigned_to)
                            @if($actionItem->status == 'pending')
                                <form action="{{ route('action-items.update-status', $actionItem) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">
                                        <i class="fas fa-play mr-1"></i> Mulai Kerjakan
                                    </button>
                                </form>
                            @elseif($actionItem->status == 'in_progress' || $actionItem->status == 'needs_revision')
                                @if($actionItem->files->count() == 0)
                                    <div class="callout callout-warning py-2 text-sm m-0 mb-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> <strong>Pekerjaan berlangsung.</strong><br>
                                        Wajib <a href="#uploadFileModal" data-toggle="modal" class="text-primary font-weight-bold">Upload File Bukti</a> di bawah sebelum melaporkan selesai.
                                    </div>
                                @else
                                    <form action="{{ route('action-items.update-status', $actionItem) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="waiting_review">
                                        <button type="submit" class="btn btn-warning btn-sm w-100 mb-2" onclick="return confirm('Kirim ke penyelenggara untuk direview?')">
                                            <i class="fas fa-paper-plane mr-1"></i> Lapor Selesai (Minta Review)
                                        </button>
                                    </form>
                                @endif
                            @elseif($actionItem->status == 'waiting_review')
                                <div class="callout callout-info py-2 text-sm m-0 mb-2">
                                    <i class="fas fa-hourglass-half fa-spin mr-1"></i> Menunggu direview Penyelenggara.
                                </div>
                            @endif
                        @endif

                        @if(auth()->user()->isAdmin() || (isset($actionItem->meeting) && auth()->id() == $actionItem->meeting->organizer_id))
                            @if($actionItem->status == 'waiting_review')
                                <div class="callout callout-info py-2 text-sm mb-2">
                                    <i class="fas fa-bell mr-1"></i> Penerima tugas telah melaporkan selesai. Silakan cek file bukti di bawah.
                                </div>

                                <form action="{{ route('action-items.update-status', $actionItem) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-success btn-sm w-100 mb-2" onclick="return confirm('Tugas sudah sesuai? Tutup tugas ini.')">
                                        <i class="fas fa-check-double mr-1"></i> Verifikasi & Tutup Tugas
                                    </button>
                                </form>
                                <form action="{{ route('action-items.update-status', $actionItem) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="needs_revision">
                                    <div class="form-group mb-2">
                                        <textarea name="revision_notes" class="form-control form-control-sm" rows="3" required placeholder="Catatan revisi untuk penerima tugas..." style="display: none;" id="revision_notes_input"></textarea>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 mb-2" onclick="document.getElementById('revision_notes_input').style.display = 'block'; this.style.display = 'none'; document.getElementById('submit_revision_btn').style.display = 'block';">
                                        <i class="fas fa-undo mr-1"></i> Tolak (Minta Revisi)
                                    </button>
                                    <button type="submit" class="btn btn-danger btn-sm w-100 mb-2" id="submit_revision_btn" style="display: none;">
                                        <i class="fas fa-paper-plane mr-1"></i> Kirim Revisi
                                    </button>
                                </form>
                            @elseif(in_array($actionItem->status, ['pending', 'in_progress', 'needs_revision']))
                                <form action="{{ route('action-items.update-status', $actionItem) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Yakin membatalkan tugas ini secara paksa?')">
                                        <i class="fas fa-ban mr-1"></i> Batalkan Tugas
                                    </button>
                                </form>
                            @endif
                        @endif

                    @endif
                </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h3 class="card-title m-0">
                    <i class="fas fa-bolt mr-2 text-primary"></i>Aksi Cepat
                </h3>
            </div>
            <div class="card-body">
                <div>
                    <a href="{{ route('action-items.index') }}" class="btn btn-secondary btn-sm mb-2 w-100 text-left">
                        <i class="fas fa-list mr-2"></i> Semua Tindak Lanjut
                    </a>
                    
                    @if($actionItem->meeting)
                    <a href="{{ route('meetings.show', $actionItem->meeting) }}" class="btn btn-info btn-sm mb-2 w-100 text-left">
                        <i class="fas fa-users mr-2"></i> Ke Meeting Asal
                    </a>
                    @endif
                    
                    @if(auth()->user()->isAdmin() || (isset($actionItem->meeting) && $actionItem->meeting->organizer_id == auth()->id()))
                    <form action="{{ route('action-items.destroy', $actionItem) }}" method="POST" class="d-inline" onsubmit="return confirmDeleteActionItem('{{ addslashes($actionItem->title) }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100 text-left">
                            <i class="fas fa-trash mr-2"></i> Hapus Tindak Lanjut
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin() || (isset($actionItem->meeting) && auth()->id() == $actionItem->meeting->organizer_id))
<div class="modal fade" id="revisiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-3">
                <h5 class="modal-title m-0"><i class="fas fa-undo mr-2"></i>Tolak Laporan & Minta Revisi</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('action-items.update-status', $actionItem) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="needs_revision">
                <div class="modal-body">
                    <div class="alert alert-warning text-sm">
                        <i class="fas fa-info-circle mr-1"></i> Status tugas akan diubah menjadi <b>Perlu Revisi</b>.
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold small">Catatan Revisi untuk Penerima Tugas *</label>
                        <textarea name="revision_notes" class="form-control" rows="4" required placeholder="Contoh: Tolong perbaiki file laporan bulanannya, masih ada data yang kurang..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-paper-plane mr-1"></i>Kirim Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if($actionItem->assigned_to == auth()->id())
<div class="modal fade" id="uploadFileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title m-0"><i class="fas fa-upload mr-2"></i>Upload File Bukti</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('action-items.upload-file', $actionItem) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="files" class="font-weight-bold small">File * <small class="text-muted font-weight-normal">(bisa pilih lebih dari 1)</small></label>
                        <div class="custom-file" id="customFileWrapper">
                            <input type="file" class="custom-file-input" id="files" name="files[]" multiple required>
                            <label class="custom-file-label" for="files" id="fileLabel">Pilih file...</label>
                        </div>
                        <small class="form-text text-muted"><i class="fas fa-info-circle mr-1"></i>Maksimal 10MB per file</small>

                        {{-- File Preview List --}}
                        <div id="filePreviewArea" class="file-preview-card mt-2" style="display: none;">
                            <div class="border border-success rounded" style="background-color: #f0fff4;">
                                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom" style="background-color: #e8f5e9;">
                                    <small class="font-weight-bold text-success">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <span id="fileCount">0</span> file siap diupload
                                        <span class="text-muted ml-1">(<span id="fileTotalSize">0 KB</span>)</span>
                                    </small>
                                    <button type="button" class="btn btn-sm text-danger p-0" id="fileClearAllBtn" title="Hapus semua" style="font-size: 0.75rem;">
                                        <i class="fas fa-trash-alt mr-1"></i>Hapus Semua
                                    </button>
                                </div>
                                <div id="fileListContainer" style="max-height: 200px; overflow-y: auto;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="font-weight-bold small">Deskripsi File</label>
                        <textarea class="form-control form-control-sm" id="description" name="description" rows="2" placeholder="Deskripsi singkat tentang file ini"></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="uploadSubmitBtn"><i class="fas fa-upload mr-1"></i>Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- File Preview Modal --}}
<div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" style="border: none; border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-2 px-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center text-white" style="min-width: 0;">
                    <i class="fas fa-eye mr-2"></i>
                    <span id="previewModalTitle" class="font-weight-bold text-truncate" style="font-size: 0.9rem;">Preview</span>
                </div>
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <a href="#" id="previewDownloadBtn" class="btn btn-sm btn-light" title="Download" style="font-size: 0.75rem;">
                        <i class="fas fa-download mr-1"></i>Download
                    </a>
                    <a href="#" id="previewNewTabBtn" target="_blank" class="btn btn-sm btn-outline-light" title="Buka di tab baru" style="font-size: 0.75rem;">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <button type="button" class="close text-white ml-0" data-dismiss="modal" style="opacity: 1; text-shadow: none;">
                        <span style="font-size: 1.5rem;">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body p-0" id="previewModalBody" style="min-height: 400px; max-height: 80vh; overflow: auto; background-color: #1a1a2e; display: flex; align-items: center; justify-content: center;">
                {{-- Content loaded dynamically --}}
                <div id="previewLoading" class="text-center py-5">
                    <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;"></div>
                    <p class="text-light mt-3 mb-0">Memuat preview...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.btn-danger {
    transition: all 0.3s ease;
}
.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
    transform: scale(1.05);
}
.fa-spinner {
    animation: spin 1s linear infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #e3e6f0;
}
/* File Attachment Styles */
.file-attachment-item {
    transition: background-color 0.15s ease;
    border-left: 3px solid transparent;
}
.file-attachment-item:hover {
    background-color: #f8f9ff;
    border-left-color: #4e73df;
}
.file-icon-box {
    transition: all 0.2s ease;
}
.file-attachment-item:hover .file-icon-box {
    background-color: #e0e7ff !important;
    transform: scale(1.05);
}
.file-description {
    word-break: break-word;
    line-height: 1.5;
}
/* File Upload Preview Card */
.file-preview-card {
    opacity: 0;
    transform: translateY(-8px);
    transition: opacity 0.25s ease, transform 0.25s ease;
}
.file-preview-card.show {
    opacity: 1;
    transform: translateY(0);
}
.custom-file.file-selected .custom-file-label {
    border-color: #28a745;
    color: #28a745;
}
.custom-file.file-selected .custom-file-label::after {
    background-color: #28a745;
    border-color: #28a745;
    color: #fff;
    content: "\2713";
}
.file-preview-row {
    transition: background-color 0.15s ease;
}
.file-preview-row:hover {
    background-color: #e8f5e9;
}
.file-remove-btn:hover {
    background-color: #ffeaea;
    border-radius: 50%;
}
/* File Preview Modal */
#filePreviewModal .modal-content {
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
}
#filePreviewModal .modal-header {
    border-bottom: none;
}
#filePreviewModal .modal-body img,
#filePreviewModal .modal-body video {
    animation: fadeInPreview 0.3s ease;
}
@keyframes fadeInPreview {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
#fileClearAllBtn:hover {
    text-decoration: underline;
}
</style>
@endpush

@push('scripts')
<script>
// === File Preview Modal Logic ===
(function() {
    const modal = document.getElementById('filePreviewModal');
    if (!modal) return;

    const modalTitle = document.getElementById('previewModalTitle');
    const modalBody = document.getElementById('previewModalBody');
    const downloadBtn = document.getElementById('previewDownloadBtn');
    const newTabBtn = document.getElementById('previewNewTabBtn');
    const loadingEl = document.getElementById('previewLoading');

    const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
    const videoExts = ['mp4', 'webm', 'ogg', 'mov'];
    const audioExts = ['mp3', 'wav', 'ogg', 'aac', 'flac'];
    const pdfExts = ['pdf'];
    const textExts = ['txt', 'csv', 'json', 'xml', 'log', 'md'];

    function getExt(filename) {
        return filename.split('.').pop().toLowerCase();
    }

    function buildPreviewContent(url, filename, mimeType, fileSize, downloadUrl) {
        const ext = getExt(filename);

        if (imageExts.includes(ext)) {
            return `<img src="${url}" alt="${filename}" style="max-width: 100%; max-height: 78vh; object-fit: contain; display: block; margin: auto;" onload="document.getElementById('previewLoading').style.display='none'" onerror="this.outerHTML='<div class=\'text-center py-5 text-light\'><i class=\'fas fa-exclamation-triangle fa-3x mb-3\'></i><p>Gagal memuat gambar</p></div>'">`;
        }

        if (pdfExts.includes(ext)) {
            return `<iframe src="${url}" style="width: 100%; height: 78vh; border: none;" onload="document.getElementById('previewLoading').style.display='none'"></iframe>`;
        }

        if (videoExts.includes(ext)) {
            return `<video controls autoplay style="max-width: 100%; max-height: 78vh; display: block; margin: auto;" onloadeddata="document.getElementById('previewLoading').style.display='none'">
                <source src="${url}" type="${mimeType || 'video/mp4'}">
                Browser Anda tidak mendukung tag video.
            </video>`;
        }

        if (audioExts.includes(ext)) {
            return `<div class="text-center py-5" style="width: 100%;">
                <i class="fas fa-music fa-4x text-light mb-4" style="opacity: 0.5;"></i>
                <p class="text-light mb-4">${filename}</p>
                <audio controls autoplay style="width: 80%; max-width: 500px;" onloadeddata="document.getElementById('previewLoading').style.display='none'">
                    <source src="${url}" type="${mimeType || 'audio/mpeg'}">
                </audio>
            </div>`;
        }

        if (textExts.includes(ext)) {
            // Fetch and display text content
            fetch(url)
                .then(r => r.text())
                .then(text => {
                    loadingEl.style.display = 'none';
                    modalBody.innerHTML = `<pre style="color: #e0e0e0; padding: 20px; margin: 0; width: 100%; overflow: auto; font-size: 0.85rem; white-space: pre-wrap; word-wrap: break-word;">${text.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</pre>`;
                })
                .catch(() => {
                    loadingEl.style.display = 'none';
                    modalBody.innerHTML = '<div class="text-center py-5 text-light"><i class="fas fa-exclamation-triangle fa-3x mb-3"></i><p>Gagal memuat file teks</p></div>';
                });
            return ''; // Content loaded async
        }

        // Unsupported / non-previewable file type - show info card
        loadingEl.style.display = 'none';
        const iconMap = {
            'zip': 'fa-file-archive', 'rar': 'fa-file-archive', '7z': 'fa-file-archive',
            'doc': 'fa-file-word', 'docx': 'fa-file-word',
            'xls': 'fa-file-excel', 'xlsx': 'fa-file-excel',
            'ppt': 'fa-file-powerpoint', 'pptx': 'fa-file-powerpoint',
        };
        const colorMap = {
            'doc': 'color: #2b579a', 'docx': 'color: #2b579a',
            'xls': 'color: #217346', 'xlsx': 'color: #217346',
            'ppt': 'color: #d24726', 'pptx': 'color: #d24726',
        };
        const icon = iconMap[ext] || 'fa-file';
        const iconColor = colorMap[ext] || '';
        return `<div class="text-center py-5" style="width: 100%;">
            <i class="fas ${icon} fa-5x mb-4" style="opacity: 0.6; ${iconColor || 'color: rgba(255,255,255,0.3)'}"></i>
            <h5 class="text-light mb-2">${filename}</h5>
            <p class="text-muted mb-1">Ukuran: ${fileSize}</p>
            <p class="text-muted mb-4">Tipe file ini tidak dapat di-preview langsung</p>
            <a href="${downloadUrl}" class="btn btn-primary">
                <i class="fas fa-download mr-2"></i>Download File
            </a>
        </div>`;
    }

    // Bind click to all preview buttons
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.file-preview-btn');
        if (!btn) return;

        const url = btn.dataset.url;
        const name = btn.dataset.name;
        const type = btn.dataset.type;
        const size = btn.dataset.size;
        const downloadUrl = btn.dataset.download;

        // Set modal info
        modalTitle.textContent = name;
        downloadBtn.href = downloadUrl;
        newTabBtn.href = url;

        // Reset and show loading
        loadingEl.style.display = '';
        const content = buildPreviewContent(url, name, type, size, downloadUrl);
        if (content) {
            modalBody.innerHTML = loadingEl.outerHTML + content;
        }

        // Open modal
        $('#filePreviewModal').modal('show');
    });

    // Clean up on modal close
    $('#filePreviewModal').on('hidden.bs.modal', function() {
        modalBody.innerHTML = loadingEl.outerHTML;
    });
})();

// === File Upload Preview Logic (Multiple Files) ===
(function() {
    const fileInput = document.getElementById('files');
    const fileLabel = document.getElementById('fileLabel');
    const previewArea = document.getElementById('filePreviewArea');
    const fileListContainer = document.getElementById('fileListContainer');
    const fileCountEl = document.getElementById('fileCount');
    const fileTotalSizeEl = document.getElementById('fileTotalSize');
    const clearAllBtn = document.getElementById('fileClearAllBtn');
    const customFileWrapper = document.getElementById('customFileWrapper');
    const uploadSubmitBtn = document.getElementById('uploadSubmitBtn');

    if (!fileInput) return;

    // Stored files (DataTransfer to manage file list)
    let selectedFiles = new DataTransfer();

    const iconMap = {
        'pdf': ['fa-file-pdf', 'text-danger'],
        'doc': ['fa-file-word', 'text-primary'],
        'docx': ['fa-file-word', 'text-primary'],
        'xls': ['fa-file-excel', 'text-success'],
        'xlsx': ['fa-file-excel', 'text-success'],
        'ppt': ['fa-file-powerpoint', 'text-warning'],
        'pptx': ['fa-file-powerpoint', 'text-warning'],
        'jpg': ['fa-file-image', 'text-info'],
        'jpeg': ['fa-file-image', 'text-info'],
        'png': ['fa-file-image', 'text-info'],
        'gif': ['fa-file-image', 'text-info'],
        'zip': ['fa-file-archive', 'text-secondary'],
        'rar': ['fa-file-archive', 'text-secondary'],
    };

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        return iconMap[ext] || ['fa-file', 'text-muted'];
    }

    function renderFileList() {
        const files = selectedFiles.files;
        
        if (files.length === 0) {
            clearAll();
            return;
        }

        // Update label
        fileLabel.textContent = files.length === 1 
            ? files[0].name 
            : files.length + ' file dipilih';

        // Calculate total size
        let totalSize = 0;
        for (let i = 0; i < files.length; i++) {
            totalSize += files[i].size;
        }

        fileCountEl.textContent = files.length;
        fileTotalSizeEl.textContent = formatFileSize(totalSize);

        // Build file list HTML
        let html = '';
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const [iconClass, colorClass] = getFileIcon(file.name);
            const isOversize = file.size > 10 * 1024 * 1024;
            
            html += `<div class="d-flex align-items-center px-3 py-2 file-preview-row ${i < files.length - 1 ? 'border-bottom' : ''}" data-index="${i}">
                <div class="mr-2 flex-shrink-0" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas ${iconClass} ${colorClass}"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="text-truncate" style="font-size: 0.82rem; font-weight: 600;" title="${file.name}">${file.name}</div>
                    <small class="${isOversize ? 'text-danger' : 'text-muted'}">
                        ${formatFileSize(file.size)}${isOversize ? ' <i class="fas fa-exclamation-triangle"></i> Melebihi 10MB!' : ''}
                    </small>
                </div>
                <button type="button" class="btn btn-sm text-danger p-0 ml-2 flex-shrink-0 file-remove-btn" data-index="${i}" title="Hapus file ini" style="width: 22px; height: 22px; line-height: 1;">
                    <i class="fas fa-times" style="font-size: 0.7rem;"></i>
                </button>
            </div>`;
        }
        fileListContainer.innerHTML = html;

        // Show area with animation
        previewArea.style.display = 'block';
        requestAnimationFrame(() => previewArea.classList.add('show'));
        customFileWrapper.classList.add('file-selected');

        // Bind individual remove buttons
        fileListContainer.querySelectorAll('.file-remove-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                removeFileAt(parseInt(this.dataset.index));
            });
        });
    }

    function removeFileAt(index) {
        const newDt = new DataTransfer();
        const files = selectedFiles.files;
        for (let i = 0; i < files.length; i++) {
            if (i !== index) newDt.items.add(files[i]);
        }
        selectedFiles = newDt;
        fileInput.files = selectedFiles.files;
        renderFileList();
    }

    function clearAll() {
        selectedFiles = new DataTransfer();
        fileInput.files = selectedFiles.files;
        fileLabel.textContent = 'Pilih file...';
        fileListContainer.innerHTML = '';
        previewArea.classList.remove('show');
        setTimeout(() => { previewArea.style.display = 'none'; }, 200);
        customFileWrapper.classList.remove('file-selected');
    }

    // File input change
    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            // Append new files to existing selection
            for (let i = 0; i < this.files.length; i++) {
                selectedFiles.items.add(this.files[i]);
            }
            fileInput.files = selectedFiles.files;
            renderFileList();
        }
    });

    // Clear all button
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            clearAll();
        });
    }

    // Reset on modal close
    $('#uploadFileModal').on('hidden.bs.modal', function() {
        clearAll();
    });
})();

// Hapus Konfirmasi
function confirmDeleteActionItem(title) {
    return confirm(`Hapus tindak lanjut "${title}"?\n\nTindakan ini tidak dapat dibatalkan dan semua data terkait akan dihapus permanen!`);
}

// Loading state untuk hapus
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('form[action*="action-items"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            if (button && button.innerHTML.includes('fa-trash')) {
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menghapus...';
                button.disabled = true;
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 5000);
            }
        });
    });
});
</script>
@endpush