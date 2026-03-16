<!-- resources/views/departments/index.blade.php -->
@extends('layouts.app')

@section('title', 'Departemen')

@section('hide_header', true)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Departemen</h3>
        <div class="card-tools">
            <a href="{{ route('departments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Departemen
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="text-center" style="background-color: #2c3e50; color: white !important;">
                <tr>


                    <th class="table-header-custom">Nama</th>
                    <th class="table-header-custom">Deskripsi</th>
                    <th class="table-header-custom">Jumlah Pengguna</th>
                    <th class="table-header-custom">Status</th>
                    <th class="table-header-custom" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                <tr>
                    <td class="align-middle">{{ $department->name }}</td>
                    <td class="align-middle">{{ $department->description ?? '-' }}</td>
                    <td class="text-center align-middle">
                        <span class="badge badge-info">{{ $department->users_count }}</span>
                    </td>
                    <td class="text-center align-middle">
                        <span class="badge badge-{{ $department->is_active ? 'success' : 'danger' }}">
                            {{ $department->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </td>
                    <td class="text-center align-middle">
                        <a href="{{ route('departments.show', $department) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Hapus departemen ini?')">
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