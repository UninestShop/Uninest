@extends('admin.layouts.app')

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Edit Permissions for {{ $user->name }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.permissions.update', $user) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Assign Roles</label>
                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" 
                                           name="roles[]" value="{{ $role->id }}" 
                                           id="role_{{ $role->id }}"
                                           {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                        {{ $role->name }}
                                    </label>
                                    <small class="d-block text-muted">
                                        Permissions: {{ $role->permissions->pluck('name')->join(', ') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update Permissions</button>
                    <a href="{{ route('admin.users.permissions') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
