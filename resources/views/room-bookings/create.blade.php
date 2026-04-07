@extends('layouts.app')

@section('title', 'Pesan Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('room-bookings.index') }}">Pinjam Ruangan</a></li>
    <li class="breadcrumb-item active">Pesan Baru</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0 rounded-xl overflow-hidden">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-box-indigo bg-soft-primary text-primary rounded-circle p-2 d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px;">
                        <i class="fas fa-door-open fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="card-title font-weight-bold mb-1 text-dark">Form Peminjaman Ruangan</h4>
                        <p class="text-xs text-muted mb-0">Isi data di bawah untuk meminjam ruangan (status otomatis disetujui jika tidak ada jadwal bentrok).</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 bg-light">
                <form action="{{ route('room-bookings.store') }}" method="POST" id="bookingForm">
                    @csrf
                    
                    <div class="bg-white p-4 rounded-xl shadow-sm mb-4">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark text-sm">Nama Peminjam / PIC</label>
                                    <input type="text" name="pic_name" class="form-control form-control-lg text-sm @error('pic_name') is-invalid @enderror" value="{{ old('pic_name', auth()->user()->name) }}" placeholder="Contoh: Pak Direktur, atau Tim IT">
                                    <small class="form-text text-muted">Biarkan sesuai nama Anda jika Anda sendiri yang akan menggunakan ruangan.</small>
                                    @error('pic_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark text-sm">Nama/Lokasi Ruangan <span class="text-danger">*</span></label>
                                    <input type="text" name="location" id="location" class="form-control form-control-lg text-sm @error('location') is-invalid @enderror" value="{{ old('location') }}" required placeholder="Contoh: Ruang Meeting Utama, Ruang Diskusi Lt. 2">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark text-sm">Tujuan Peminjaman <span class="text-danger">*</span></label>
                                    <input type="text" name="purpose" class="form-control form-control-lg text-sm @error('purpose') is-invalid @enderror" value="{{ old('purpose', 'Diskusi Internal') }}" required placeholder="Contoh: Diskusi Internal Tim IT">
                                    @error('purpose')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm mb-4">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="far fa-clock mr-2 text-primary"></i>Jadwal Peminjaman</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark text-sm">Waktu Mulai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" id="start_time" name="start_time" class="form-control form-control-lg text-sm @error('start_time') is-invalid @enderror" value="{{ old('start_time', now()->format('Y-m-d\TH:i')) }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark text-sm">Waktu Selesai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" id="end_time" name="end_time" class="form-control form-control-lg text-sm @error('end_time') is-invalid @enderror" value="{{ old('end_time', now()->addHours(1)->format('Y-m-d\TH:i')) }}" required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('room-bookings.index') }}" class="btn btn-light px-4 shadow-sm font-weight-bold" style="border-radius: 10px;">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 shadow font-weight-bold" style="border-radius: 10px;">
                            <i class="fas fa-check mr-1"></i> Pesan Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Widget Cek Ketersediaan -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 rounded-xl overflow-hidden sticky-top" style="top: 20px;">
            <div class="card-header bg-white border-bottom-0 p-4 pb-0">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box-indigo bg-indigo-soft text-indigo rounded-lg p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
                        <i class="fas fa-door-open fa-lg"></i>
                    </div>
                    <h5 class="mb-0 font-weight-bold text-dark" style="font-size: 1.15rem;">Status Ruangan</h5>
                </div>
                <hr class="m-0 border-light">
            </div>
            <div class="card-body p-4 bg-white" style="min-height: 250px;">
                <div id="availabilityHeader" class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3 d-none">
                    <div class="font-weight-bold text-dark text-truncate mr-2" id="availabilityLocName" style="font-size: 1.05rem;"></div>
                    <div id="availabilityBadge"></div>
                </div>
                
                <div class="text-muted text-sm" id="availabilityPrompt">
                    <i class="fas fa-info-circle mr-1"></i>Pilih lokasi dan tanggal untuk melihat jadwal yang sudah terisi.
                </div>
                
                <!-- Tempat hasil AJAX -->
                <div id="availabilityList" class="position-relative mt-2">
                    <div class="text-center text-muted">
                        <div class="spinner-border text-primary spinner-border-sm d-none" id="availabilityLoader" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-indigo-soft { background-color: rgba(79, 70, 229, 0.1); }
    .text-indigo { color: #4f46e5; }
    .bg-emerald-soft { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }
    .bg-danger-soft { background-color: rgba(239, 68, 68, 0.1) !important; color: #ef4444 !important; }
    
    .timeline-item {
        position: relative;
        padding-left: 20px;
        margin-bottom: 15px;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: 4px;
        top: 8px;
        bottom: -20px;
        width: 2px;
        background-color: #cbd5e1;
    }
    .timeline-item:last-child:before {
        display: none;
    }
    .timeline-dot {
        position: absolute;
        left: 0;
        top: 6px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #64748b;
    }
    .timeline-item.active .timeline-dot {
        background-color: #ef4444;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        let startFP = flatpickr("#start_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                let endPicker = document.querySelector("#end_time")._flatpickr;
                let endDate = new Date(selectedDates[0]);
                endDate.setHours(endDate.getHours() + 1);
                endPicker.set("minDate", selectedDates[0]);
                
                let currentEnd = new Date(endPicker.selectedDates[0]);
                if(currentEnd <= selectedDates[0]) {
                    endPicker.setDate(endDate);
                }
                
                checkAvailability();
            }
        });
        
        flatpickr("#end_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: "today"
        });
        
        let timeout = null;
        $('#location').on('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                checkAvailability();
            }, 500);
        });
        
        function checkAvailability() {
            let loc = $('#location').val();
            let dateVal = $('#start_time').val();
            
            if (!loc || !dateVal) return;
            
            // Extract Y-m-d from datetime string
            let dateObj = dateVal.split(' ')[0];
            
            $('#availabilityHeader').removeClass('d-none');
            $('#availabilityLocName').text(loc);
            $('#availabilityPrompt').hide();
            
            $('#availabilityLoader').removeClass('d-none');
            $('#availabilityList').find('.timeline-item').remove();
            $('#availabilityList').find('.empty-state').remove();
            $('#availabilityBadge').empty();
            
            $.ajax({
                url: '{{ route("meetings.booked-slots") }}',
                method: 'GET',
                data: {
                    location: loc,
                    date: dateObj
                },
                success: function(response) {
                    $('#availabilityLoader').addClass('d-none');
                    if (response.length === 0) {
                        $('#availabilityBadge').html('<span class="badge badge-pill bg-emerald-soft font-weight-bold" style="padding: 6px 12px;">Tersedia</span>');
                        $('#availabilityList').append('<div class="empty-state text-center py-4 text-muted"><i class="fas fa-check-circle fa-2x text-emerald mb-2 opacity-50"></i><br>Tidak ada jadwal</div>');
                    } else {
                        $('#availabilityBadge').html('<span class="badge badge-pill bg-danger-soft font-weight-bold" style="padding: 6px 12px;">Terjadwal</span>');
                        // Sort response by start time
                        response.sort((a,b) => (a.start_time > b.start_time) ? 1 : -1);
                        
                        let html = '';
                        response.forEach(function(item) {
                            let s = new Date(item.start_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            let e = new Date(item.end_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            html += `
                                <div class="timeline-item">
                                    <div class="timeline-dot"></div>
                                    <div class="font-weight-bold text-dark mb-1" style="font-size: 0.95rem;">${s} - ${e}</div>
                                    <div class="text-muted" style="font-size: 0.85rem;">Reservasi: ${item.title}</div>
                                </div>
                            `;
                        });
                        $('#availabilityList').append(html);
                    }
                },
                error: function() {
                    $('#availabilityLoader').addClass('d-none');
                    $('#availabilityList').append('<div class="empty-state text-center py-3 text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Gagal memuat jadwal.</div>');
                }
            });
        }
        
        // Initial check if values are present (e.g. going back on validation error)
        if ($('#location').val() && $('#start_time').val()) {
            checkAvailability();
        }
    });
</script>
@endpush
