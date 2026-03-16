<!-- resources/views/meetings/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Meeting')

@section('hide_header', true)

@section('content')
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 font-weight-bold text-dark mb-1">Edit Rapat</h1>
            <p class="text-muted mb-0">Perbarui detail rapat Anda.</p>
        </div>
        <div class="d-flex">
            <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-light px-4 py-2 rounded-lg font-weight-bold mr-2">
                <i class="fas fa-eye mr-2"></i> Lihat Rapat
            </a>
            <a href="{{ route('meetings.index') }}" class="btn btn-light px-4 py-2 rounded-lg font-weight-bold">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <form action="{{ route('meetings.update', $meeting) }}" method="POST" id="meetingForm">
        @csrf
        @method('PUT')
        <div class="row gx-5">
            <!-- Left Column: Primary Details -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4 rounded-xl">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold text-dark mb-4 pb-2 border-bottom">
                            <i class="fas fa-info-circle text-primary mr-2"></i> Informasi Umum
                        </h5>
                        
                        <div class="form-group mb-4">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Judul Rapat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg border-light bg-light rounded-lg @error('title') is-invalid @enderror" 
                                   name="title" value="{{ old('title', $meeting->title) }}" placeholder="Contoh: Sinkronisasi Mingguan" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Jenis Rapat <span class="text-danger">*</span></label>
                                    <select class="form-control border-light bg-light rounded-lg @error('meeting_type_id') is-invalid @enderror" name="meeting_type_id" required>
                                        <option value="">Pilih Jenis</option>
                                        @foreach($meetingTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('meeting_type_id', $meeting->meeting_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('meeting_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Departemen <span class="text-danger">*</span></label>
                                    <select class="form-control border-light bg-light rounded-lg @error('department_id') is-invalid @enderror" name="department_id" required>
                                        <option value="">Pilih Departemen</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $meeting->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Deskripsi</label>
                            <textarea class="form-control border-light bg-light rounded-lg @error('description') is-invalid @enderror" 
                                      name="description" rows="4" placeholder="Jelaskan secara singkat tujuan rapat ini...">{{ old('description', $meeting->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4 rounded-xl">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                            <h5 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-calendar-alt text-primary mr-2"></i> Tanggal & Lokasi
                            </h5>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_online" name="is_online" value="1" {{ old('is_online', $meeting->is_online) ? 'checked' : '' }}>
                                <label class="custom-control-label text-xs font-weight-bold text-uppercase text-muted pt-1" for="is_online">Rapat Online</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Waktu Mulai <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control border-light bg-light rounded-lg @error('start_time') is-invalid @enderror" 
                                           id="start_time" name="start_time" value="{{ old('start_time', $meeting->start_time->format('Y-m-d H:i')) }}" required placeholder="Pilih waktu mulai">
                                    @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Waktu Selesai <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control border-light bg-light rounded-lg @error('end_time') is-invalid @enderror" 
                                           id="end_time" name="end_time" value="{{ old('end_time', $meeting->end_time->format('Y-m-d H:i')) }}" required placeholder="Pilih waktu selesai">
                                    @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div id="booked-status" class="mt-2" style="display: none;">
                                        <div class="booked-info-badge mb-2">
                                            <i class="fas fa-info-circle mr-1"></i> Beberapa waktu sudah dibooking pada hari ini.
                                        </div>
                                        <div id="booked-time-list" class="small text-danger font-weight-bold">
                                            <!-- Booked times will be listed here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="locationField" class="form-group mb-0" style="{{ old('is_online', $meeting->is_online) ? 'display: none;' : '' }}">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-light bg-light rounded-lg @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location', $meeting->location) }}" placeholder="Nama ruangan atau alamat">
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div id="onlineMeetingSection" style="{{ old('is_online', $meeting->is_online) ? 'display: block;' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Tautan Rapat <span class="text-danger">*</span></label>
                                        <input type="url" class="form-control border-light bg-light rounded-lg @error('meeting_link') is-invalid @enderror" 
                                               id="meeting_link" name="meeting_link" value="{{ old('meeting_link', $meeting->meeting_link) }}" placeholder="https://...">
                                        @error('meeting_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Platform</label>
                                        <select class="form-control border-light bg-light rounded-lg" name="meeting_platform">
                                            <option value="">Pilih Platform</option>
                                            <option value="google_meet" {{ old('meeting_platform', $meeting->meeting_platform) == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                                            <option value="zoom" {{ old('meeting_platform', $meeting->meeting_platform) == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                            <option value="microsoft_teams" {{ old('meeting_platform', $meeting->meeting_platform) == 'microsoft_teams' ? 'selected' : '' }}>Teams</option>
                                            <option value="other" {{ old('meeting_platform', $meeting->meeting_platform) == 'other' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">ID</label>
                                        <input type="text" class="form-control border-light bg-light rounded-lg" name="meeting_id" value="{{ old('meeting_id', $meeting->meeting_id) }}" placeholder="ID Rapat">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Kode Akses</label>
                                        <input type="text" class="form-control border-light bg-light rounded-lg" name="meeting_password" value="{{ old('meeting_password', $meeting->meeting_password) }}" placeholder="Kode Akses">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Participants & Action -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4 rounded-xl sticky-top" style="top: 100px; z-index: 10;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold text-dark mb-4 pb-2 border-bottom">
                            <i class="fas fa-users text-primary mr-2"></i> Peserta
                        </h5>
                        
                        <div class="form-group mb-5">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 letter-spacing-1">Pilih Anggota <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('participants') is-invalid @enderror" 
                                    id="participants" name="participants[]" multiple="multiple" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, old('participants', $currentParticipants)) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('participants')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <hr class="mb-4">

                        <button type="submit" class="btn btn-emerald btn-block py-3 rounded-lg font-weight-bold shadow-sm transition-all hover-translate-y mb-3">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                        
                        @if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
                        <button type="button" class="btn btn-outline-danger btn-block py-2 rounded-lg font-weight-bold" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash mr-2"></i> Arsipkan Rapat
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Konfirmasi Hapus -->
@if(auth()->user()->canManageMeetings() || $meeting->organizer_id == auth()->id())
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus meeting ini?</p>
                <p><strong>{{ $meeting->title }}</strong></p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus Meeting</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
    </form>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .rounded-xl { border-radius: 12px !important; }
    .btn-emerald { background-color: #10b981; color: white; }
    .btn-emerald:hover { background-color: #059669; color: white; }
    .text-emerald { color: #10b981; }
    .bg-emerald-soft { background: rgba(16, 185, 129, 0.1); }
    .letter-spacing-1 { letter-spacing: 0.05em; }
    .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #f1f5f9;
        background-color: #f8fafc;
        border-radius: 8px;
        min-height: 45px;
        padding: 4px;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #10b981;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #10b981;
        border: none;
        color: white;
        border-radius: 6px;
        padding: 4px 12px 4px 24px !important; /* Proper padding for text and icon */
        margin-top: 4px;
        position: relative;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white !important;
        border: none !important;
        background: transparent !important;
        position: absolute;
        left: 6px;
        top: 50%;
        transform: translateY(-50%);
        margin-right: 0;
        padding: 0 4px;
        font-weight: bold;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ef4444 !important;
    }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-3px); }

    /* Booked slot highlighting */
    .booked-date-highlight {
        background-color: #fee2e2 !important;
        color: #ef4444 !important;
        border-radius: 50% !important;
        position: relative;
    }
    .booked-date-highlight::after {
        content: '';
        position: absolute;
        bottom: 4px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        background-color: #ef4444;
        border-radius: 50%;
    }
    .flatpickr-day.booked-date-highlight:hover {
        background-color: #fecaca !important;
    }
    .booked-info-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 4px;
        background-color: #fee2e2;
        color: #ef4444;
        display: inline-block;
        margin-top: 8px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let bookedSlots = [];

    // Fetch booked slots
    fetch("{{ route('meetings.booked-slots') }}")
        .then(response => response.json())
        .then(data => {
            bookedSlots = data.map(slot => ({
                id: slot.id,
                start: new Date(slot.start_time),
                end: new Date(slot.end_time)
            }));
            
            if (bookedSlots.length > 1) { // Lebih dari 1 karena meeting ini sendiri dihitung
                document.getElementById('booked-status').style.display = 'block';
            }
            
            initFlatpickr();
        });

    function initFlatpickr() {
        const currentMeetingId = {{ $meeting->id }};
        const commonConfig = {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            locale: "id",
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const date = dayElem.dateObj;
                const isBooked = bookedSlots.some(slot => {
                    if (slot.id === currentMeetingId) return false;
                    const slotDay = new Date(slot.start);
                    return date.getFullYear() === slotDay.getFullYear() &&
                           date.getMonth() === slotDay.getMonth() &&
                           date.getDate() === slotDay.getDate();
                });

                if (isBooked) {
                    dayElem.classList.add("booked-date-highlight");
                    dayElem.title = "Ada rapat lain di hari ini";
                }
            }
        };

        const startTimePicker = flatpickr("#start_time", {
            ...commonConfig,
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates[0]) {
                    endTimePicker.set('minDate', dateStr);
                    updateBookedTimeList(selectedDates[0]);
                }
            }
        });

        const endTimePicker = flatpickr("#end_time", {
            ...commonConfig,
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates[0]) {
                    updateBookedTimeList(selectedDates[0]);
                }
            }
        });

        function updateBookedTimeList(selectedDate) {
            if (!selectedDate) return;
            
            const listContainer = document.getElementById('booked-time-list');
            const statusContainer = document.getElementById('booked-status');
            
            const bookedToday = bookedSlots.filter(slot => {
                if (slot.id === currentMeetingId) return false;
                const slotDay = new Date(slot.start);
                return selectedDate.getFullYear() === slotDay.getFullYear() &&
                       selectedDate.getMonth() === slotDay.getMonth() &&
                       selectedDate.getDate() === slotDay.getDate();
            });

            if (bookedToday.length > 0) {
                statusContainer.style.display = 'block';
                listContainer.innerHTML = 'Jadwal Terisi Lainnya:<br>' + bookedToday.map(slot => {
                    const start = slot.start.toLocaleTimeString('id-id', { hour: '2-digit', minute: '2-digit' });
                    const end = slot.end.toLocaleTimeString('id-id', { hour: '2-digit', minute: '2-digit' });
                    return `• ${start} - ${end}`;
                }).join('<br>');
            } else {
                statusContainer.style.display = 'none';
                listContainer.innerHTML = '';
            }
        }
    }

    // Select2 Initialization
    if ($.fn.select2) {
        $('#participants').select2({
            placeholder: 'Select members...',
            allowClear: true,
            closeOnSelect: false,
            width: '100%'
        });
    }

    // Toggle meeting online fields
    const isOnlineCheckbox = document.getElementById('is_online');
    const locationField = document.getElementById('locationField');
    const onlineMeetingSection = document.getElementById('onlineMeetingSection');
    
    function toggleMeetingType() {
        if (isOnlineCheckbox.checked) {
            locationField.style.display = 'none';
            onlineMeetingSection.style.display = 'block';
            document.getElementById('location').required = false;
        } else {
            locationField.style.display = 'block';
            onlineMeetingSection.style.display = 'none';
            document.getElementById('location').required = true;
        }
    }
    
    isOnlineCheckbox.addEventListener('change', toggleMeetingType);
    toggleMeetingType();
});
</script>
@endpush