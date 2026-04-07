@extends('layouts.app')

@section('title', 'Pengaturan Branding')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengaturan Branding</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0 rounded-xl overflow-hidden">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-box-indigo bg-soft-primary text-primary rounded-circle p-2 d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px;">
                        <i class="fas fa-paint-brush fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="card-title font-weight-bold mb-1 text-dark">Pengaturan Identitas & Branding</h4>
                        <p class="text-xs text-muted mb-0">Sesuaikan logo, favicon, dan teks aplikasi yang muncul pada layar pengguna.</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 bg-light">
                <form action="{{ route('settings.branding.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="bg-white p-4 rounded-xl shadow-sm mb-4">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-font mr-2 text-primary"></i>Penamaan Aplikasi</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark text-sm">Nama Singkat / Judul Login</label>
                                    <input type="text" name="login_title" class="form-control form-control-lg text-sm @error('login_title') is-invalid @enderror" value="{{ old('login_title', $setting->login_title ?? 'HERBATECH') }}" placeholder="Contoh: DINAS PENDIDIKAN">
                                    <small class="form-text text-muted">Teks besar yang muncul di halaman awal (Login).</small>
                                    @error('login_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark text-sm">Nama Aplikasi (Teks Header)</label>
                                    <input type="text" name="app_name" class="form-control form-control-lg text-sm @error('app_name') is-invalid @enderror" value="{{ old('app_name', $setting->app_name ?? 'HERBATECH') }}" placeholder="Contoh: MyCompany">
                                    <small class="form-text text-muted">Teks yang muncul di sebelah logo pada menu navigasi atas.</small>
                                    @error('app_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm mb-4">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-images mr-2 text-primary"></i>Logo & Icon</h6>
                        <div class="row">
                            <div class="col-md-6 mb-4 text-center">
                                <label class="font-weight-bold text-dark text-sm d-block text-left">Logo Utama</label>
                                <div class="p-3 border rounded bg-light mb-2 d-inline-block" style="min-width: 200px; min-height: 100px;">
                                    @if(isset($setting) && $setting->logo_path)
                                        <img src="{{ Storage::url($setting->logo_path) }}" alt="Logo Utama" style="max-height: 80px; max-width: 100%; object-fit: contain;">
                                    @else
                                        <img src="{{ asset('images/logo.png') }}" alt="Logo Utama Bawaan" style="max-height: 80px; max-width: 100%; object-fit: contain;">
                                    @endif
                                </div>
                                <div class="form-group text-left">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" id="customFileLogo" name="logo" accept="image/png, image/jpeg, image/svg+xml">
                                        <label class="custom-file-label" for="customFileLogo">Pilih File Logo...</label>
                                    </div>
                                    <small class="form-text text-muted">Format yang disarankan: SVG atau PNG transparan. Maks 2MB.</small>
                                    @error('logo')
                                        <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-4 text-center">
                                <label class="font-weight-bold text-dark text-sm d-block text-left">Favicon (Ikon Tab Browser)</label>
                                <div class="p-3 border rounded bg-light mb-2 d-inline-block" style="min-width: 100px; min-height: 100px; display: flex !important; align-items: center; justify-content: center;">
                                    @if(isset($setting) && $setting->favicon_path)
                                        <img src="{{ Storage::url($setting->favicon_path) }}" alt="Favicon" style="max-height: 48px; max-width: 48px; object-fit: contain;">
                                    @else
                                        <img src="{{ asset('images/favicon/favicon-32x32.png') }}" alt="Favicon Bawaan" style="max-height: 48px; max-width: 48px; object-fit: contain;">
                                    @endif
                                </div>
                                <div class="form-group text-left">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('favicon') is-invalid @enderror" id="customFileFavicon" name="favicon" accept="image/png, image/x-icon, image/jpeg">
                                        <label class="custom-file-label" for="customFileFavicon">Pilih File Ikon...</label>
                                    </div>
                                    <small class="form-text text-muted">Bentuk persegi dimensi 1:1, max 1MB.</small>
                                    @error('favicon')
                                        <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-light px-4 shadow-sm font-weight-bold" style="border-radius: 10px;">Kembali</a>
                        <button type="submit" class="btn btn-primary px-5 shadow font-weight-bold" style="border-radius: 10px;">
                            <i class="fas fa-save mr-1"></i> Simpan Pengaturan
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
    // Script to show filename on custom file input
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@endpush
