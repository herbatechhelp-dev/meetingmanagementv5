<!-- resources/views/meeting-types/index.blade.php -->
@extends('layouts.app')

@section('title', 'Jenis Meeting')

@section('breadcrumb')
    <li class="breadcrumb-item active">Jenis Meeting</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Jenis Meeting</h3>
        <div class="card-tools">
            <a href="{{ route('meeting-types.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Jenis Meeting
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead style="background-color: #2c3e50;">
                <tr>
                    <th class="table-header-custom">Nama</th>
                    <th class="table-header-custom">Deskripsi</th>
                    <th class="table-header-custom">Field Wajib</th>
                    <th class="table-header-custom">Status</th>
                    <th class="table-header-custom" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($meetingTypes as $type)
                <tr>
                    <td class="align-middle">{{ $type->name }}</td>
                    <td class="align-middle">{{ $type->description ?? '-' }}</td>
                    <td class="align-middle">
                        @if($type->required_fields)
                            @foreach($type->required_fields as $field)
                                <span class="badge badge-info">{{ $field }}</span>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center align-middle">
                        <span class="badge badge-{{ $type->is_active ? 'success' : 'danger' }}">
                            {{ $type->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </td>
                    <td class="text-center align-middle">
                        <a href="{{ route('meeting-types.edit', $type) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('meeting-types.destroy', $type) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Hapus jenis meeting ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection