@extends('layouts.app')

@section('title', 'Pengaturan Notifikasi')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-bell-slash mr-2"></i>Pengaturan Notifikasi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('notifications.index') }}">Notifikasi</a></li>
                    <li class="breadcrumb-item active">Pengaturan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <form action="{{ route('notifications.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-sliders-h mr-2"></i>Pilih Jenis Notifikasi yang Ingin Anda Terima</h3>
                    <div class="card-tools">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4">Jenis Notifikasi</th>
                                <th class="text-center" width="150">
                                    <i class="fas fa-bell mr-1 text-primary"></i>Lonceng
                                </th>
                                <th class="text-center" width="150">
                                    <i class="fas fa-envelope mr-1 text-info"></i>Email
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($types as $key => $label)
                            <tr>
                                <td class="pl-4 align-middle">
                                    <strong>{{ $label }}</strong>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="{{ $key }}_bell"
                                               name="{{ $key }}_bell"
                                               value="1"
                                               {{ ($prefs["{$key}_bell"] ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="{{ $key }}_bell"></label>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="{{ $key }}_email"
                                               name="{{ $key }}_email"
                                               value="1"
                                               {{ ($prefs["{$key}_email"] ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="{{ $key }}_email"></label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-muted text-sm">
                    <i class="fas fa-info-circle mr-1"></i>
                    Nonaktifkan toggle untuk berhenti menerima jenis notifikasi tersebut. Pengaturan berlaku segera setelah disimpan.
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
