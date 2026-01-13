@extends('admin.layouts.app')

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Roles Management</h2>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Role
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Users</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->slug }}</td>
                        <td>{{ $role->users_count }}</td>
                        <td>
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
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
</div>
@endsection
