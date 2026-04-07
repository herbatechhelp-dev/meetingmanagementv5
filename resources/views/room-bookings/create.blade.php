@extends('layouts.app')

@section('title', 'Pesan Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('room-bookings.index') }}">Pinjam Ruangan</a></li>
    <li class="breadcrumb-item active">Pesan Baru</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
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
                <form action="{{ route('room-bookings.store') }}" method="POST">
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
                                    <input type="text" name="location" class="form-control form-control-lg text-sm @error('location') is-invalid @enderror" value="{{ old('location') }}" required placeholder="Contoh: Ruang Meeting Utama, Ruang Diskusi Lt. 2">
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
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        flatpickr("#start_time", {
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
            }
        });
        
        flatpickr("#end_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: "today"
        });
    });
</script>
@endpush
