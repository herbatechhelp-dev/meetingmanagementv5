<!-- resources/views/users/index.blade.php -->
@extends('layouts.app')

@section('title', 'Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pengguna</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengguna</h3>
        <div class="card-tools">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pengguna
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead style="background-color: #2c3e50;">
                <tr>
                    <th class="table-header-custom">Nama</th>
                    <th class="table-header-custom">Email</th>
                    <th class="table-header-custom">Departemen</th>
                    <th class="table-header-custom">Posisi</th>
                    <th class="table-header-custom">Role</th>
                    <th class="table-header-custom">Status</th>
                    <th class="table-header-custom" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="align-middle">{{ $user->name }}</td>
                    <td class="align-middle">{{ $user->email }}</td>
                    <td class="align-middle">{{ $user->department->name ?? '-' }}</td>
                    <td class="align-middle">{{ $user->position }}</td>
                    <td class="text-center align-middle">
                        <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'manager' ? 'warning' : 'info') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="text-center align-middle">
                        <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                            {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </td>
                    <td class="text-center align-middle" style="white-space: nowrap;">
                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Hapus pengguna ini?')">
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