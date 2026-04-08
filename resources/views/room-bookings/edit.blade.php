@extends('layouts.app')

@section('title', 'Edit Reservasi Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('room-bookings.index') }}">Pinjam Ruangan</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Form Column -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px;">
                <div class="card-header bg-white py-3 border-0">
                    <h3 class="card-title font-weight-bold mb-0">Ubah Data Reservasi</h3>
                </div>
                <div class="card-body px-4 pb-4">
                    <form id="bookingForm" action="{{ route('room-bookings.update', $roomBooking) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-600 mb-2">Peminjam / PIC <span class="text-danger">*</span></label>
                                    <input type="text" name="pic_name" class="form-control @error('pic_name') is-invalid @enderror" value="{{ old('pic_name', $roomBooking->pic_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-600 mb-2">Pilih Ruangan <span class="text-danger">*</span></label>
                                    <select name="room_id" id="room_id" class="form-control custom-select @error('room_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Ruangan --</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" {{ old('room_id', $roomBooking->room_id) == $room->id ? 'selected' : '' }}>
                                                {{ $room->name }} (Kapasitas: {{ $room->capacity }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="location" id="location" value="{{ old('location', $roomBooking->location) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-600 mb-2">Tujuan Penggunaan <span class="text-danger">*</span></label>
                            <input type="text" name="purpose" class="form-control @error('purpose') is-invalid @enderror" value="{{ old('purpose', $roomBooking->purpose) }}" placeholder="Contoh: Meeting Internal Departemen" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-600 mb-2">Waktu Mulai <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0"><i class="far fa-calendar-alt text-muted"></i></span>
                                        </div>
                                        <input type="text" name="start_time" id="start_time" class="form-control border-left-0 @error('start_time') is-invalid @enderror" value="{{ old('start_time', $roomBooking->start_time) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-600 mb-2">Waktu Selesai <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0"><i class="far fa-clock text-muted"></i></span>
                                        </div>
                                        <input type="text" name="end_time" id="end_time" class="form-control border-left-0 @error('end_time') is-invalid @enderror" value="{{ old('end_time', $roomBooking->end_time) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('room-bookings.index') }}" class="btn btn-light px-4 py-2 mr-2 font-weight-600" style="border-radius: 10px;">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 py-2 font-weight-600 shadow-sm" style="border-radius: 10px;">
                                <i class="fas fa-save mr-1"></i> Perbarui Reservasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Availability Widget Column -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 20px;">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold mb-0">Status Ruangan</h3>
                        <div id="availabilityBadge">
                            <span class="badge badge-pill bg-light text-muted font-weight-bold px-3 py-1">Pilih Ruangan</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="availabilityLoader" class="text-center py-5 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted small">Mengecek ketersediaan...</p>
                    </div>
                    
                    <div id="availabilityList" class="timeline-container px-4 py-3">
                        <div class="empty-state text-center py-5 text-muted">
                            <i class="fas fa-door-open fa-3x mb-3 opacity-25"></i>
                            <p>Pilih ruangan dan tanggal untuk melihat jadwal hari ini.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .font-weight-600 { font-weight: 600; }
    .form-control { border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.6rem 1rem; height: auto; transition: all 0.2s; }
    .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
    .input-group-text { border-radius: 10px 0 0 10px; border: 1px solid #e2e8f0; }
    .bg-emerald-soft { background-color: rgba(16, 185, 129, 0.1); color: #10b981; }
    .bg-danger-soft { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .timeline-container { position: relative; max-height: 400px; overflow-y: auto; }
    .timeline-item { position: relative; padding-left: 30px; margin-bottom: 20px; border-left: 2px solid #e2e8f0; padding-bottom: 5px; }
    .timeline-item:last-child { margin-bottom: 0; border-left: 2px solid transparent; }
    .timeline-dot { position: absolute; left: -6px; top: 0; width: 10px; height: 10px; border-radius: 50%; background-color: #cbd5e1; border: 2px solid #fff; }
    .timeline-item.active { border-left-color: #ef4444; }
    .timeline-item.active .timeline-dot { background-color: #ef4444; }
    
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let hasClash = false;
        let startFP = flatpickr("#start_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: "today",
            onChange: function() {
                checkAvailability();
            }
        });
        
        let endFP = flatpickr("#end_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: "today",
            onChange: function() {
                checkAvailability();
            }
        });
        
        $('#room_id').on('change', function() {
            // Update hidden location field for compatibility
            let selectedText = $(this).find('option:selected').text().split(' (')[0];
            $('#location').val(selectedText);
            checkAvailability();
        });

        function checkAvailability() {
            let roomId = $('#room_id').val();
            let startTime = $('#start_time').val();
            
            if (!roomId || !startTime) return;
            
            let dateObj = startTime.split(' ')[0];
            
            $('#availabilityLoader').removeClass('d-none');
            $('#availabilityList').empty();
            
            $.ajax({
                url: "{{ route('meetings.booked-slots') }}",
                data: { room_id: roomId, date: dateObj },
                success: function(response) {
                    $('#availabilityLoader').addClass('d-none');
                    
                    if (response.length === 0) {
                        hasClash = false;
                        $('#availabilityBadge').html('<span class="badge badge-pill bg-emerald-soft font-weight-bold" style="padding: 6px 12px;">Tersedia</span>');
                        $('#availabilityList').append('<div class="empty-state text-center py-4 text-muted"><i class="fas fa-check-circle fa-2x text-emerald mb-2 opacity-50"></i><br>Tidak ada jadwal</div>');
                    } else {
                        response.sort((a,b) => (a.start_time > b.start_time) ? 1 : -1);
                        
                        let userStart = new Date($('#start_time').val());
                        let userEnd = new Date($('#end_time').val());
                        hasClash = false;
                        
                        let html = '';
                        response.forEach(function(item) {
                            // Skip current booking if it's the one we're editing
                            if (item.type === 'room_booking' && item.id == "{{ $roomBooking->id }}") {
                                return;
                            }

                            let sTime = new Date(item.start_time);
                            let eTime = new Date(item.end_time);
                            
                            let isOverlapping = userStart < eTime && userEnd > sTime;
                            if (isOverlapping) hasClash = true;
                            
                            let s = sTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            let e = eTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            
                            html += `
                                <div class="timeline-item ${isOverlapping ? 'active' : ''}">
                                    <div class="timeline-dot"></div>
                                    <div class="font-weight-bold ${isOverlapping ? 'text-danger' : 'text-dark'} mb-1" style="font-size: 0.95rem;">
                                        ${s} - ${e} ${isOverlapping ? '<span class="badge badge-danger ml-1" style="font-size: 0.6rem;">BENTROK</span>' : ''}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.85rem;">Reservasi: ${item.title}</div>
                                </div>
                            `;
                        });
                        
                        if (hasClash) {
                            $('#availabilityBadge').html('<span class="badge badge-pill bg-danger font-weight-bold text-white shadow-sm" style="padding: 6px 12px; animation: pulse 2s infinite;">BENTROK!</span>');
                        } else {
                            $('#availabilityBadge').html('<span class="badge badge-pill bg-danger-soft font-weight-bold" style="padding: 6px 12px;">Terjadwal</span>');
                        }
                        
                        $('#availabilityList').append(html || '<div class="empty-state text-center py-4 text-muted"><i class="fas fa-check-circle fa-2x text-emerald mb-2 opacity-50"></i><br>Tidak ada jadwal</div>');
                    }
                }
            });
        }
        
        $('#bookingForm').on('submit', function(e) {
            if (hasClash) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Bisa Menyimpan',
                    text: 'Jadwal yang Anda pilih bentrok dengan reservasi lain.',
                    confirmButtonColor: '#4f46e5'
                });
            }
        });

        // Initial check
        if ($('#room_id').val() && $('#start_time').val()) {
            checkAvailability();
        }
    });
</script>
@endpush
