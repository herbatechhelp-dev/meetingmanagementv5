@extends('layouts.app')

@section('title', 'Edit Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Manajemen Ruangan</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-header bg-white py-3 border-0">
                    <h3 class="card-title font-weight-bold mb-0">Edit Data Ruangan</h3>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('rooms.update', $room) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-4">
                            <label for="name" class="font-weight-600 mb-2">Nama Ruangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $room->name) }}" placeholder="Contoh: Ruang Rapat Merpati" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="capacity" class="font-weight-600 mb-2">Kapasitas (Orang)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-users text-muted small"></i></span>
                                </div>
                                <input type="number" class="form-control border-left-0 @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $room->capacity) }}" placeholder="Jumlah maksimal orang">
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="description" class="font-weight-600 mb-2">Deskripsi / Fasilitas</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Sebutkan fasilitas ruangan (Proyektor, AC, Whiteboard, dll)">{{ old('description', $room->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $room->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-600" for="is_active">Aktifkan Ruangan</label>
                            </div>
                            <small class="text-muted">Ruangan yang tidak aktif tidak akan muncul dalam pilihan peminjaman.</small>
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('rooms.index') }}" class="btn btn-light px-4 py-2 mr-2 font-weight-600" style="border-radius: 10px;">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 py-2 font-weight-600" style="border-radius: 10px;">
                                <i class="fas fa-save mr-1"></i> Perbarui Ruangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .font-weight-600 { font-weight: 600; }
    .form-control { border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.6rem 1rem; }
    .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
    .input-group-text { border-radius: 10px 0 0 10px; border: 1px solid #e2e8f0; }
</style>
@endsection
