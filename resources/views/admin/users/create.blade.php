@extends('admin.layouts.app', ['title' => 'Add User'])

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add New User</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="user_type" class="form-label">User Type</label>
                    <select class="form-select @error('user_type') is-invalid @enderror" 
                            id="user_type" name="user_type">
                        <option value="">Select User Type</option>
                        @foreach($userTypes as $type)
                            <option value="{{ $type }}" {{ old('user_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                        <option value="new_type" {{ old('user_type') == 'new_type' ? 'selected' : '' }}>
                            Add New Type...
                        </option>
                    </select>
                    @error('user_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3" id="new_type_container" style="display: none;">
                    <label for="new_user_type" class="form-label">New User Type</label>
                    <input type="text" class="form-control" id="new_user_type" name="new_user_type" value="{{ old('new_user_type') }}">
                    <div class="form-text">Enter the new user type name (lowercase, use underscores instead of spaces)</div>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" 
                           name="password_confirmation" required>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide new type field based on selection
    document.getElementById('user_type').addEventListener('change', function() {
        const newTypeContainer = document.getElementById('new_type_container');
        if (this.value === 'new_type') {
            newTypeContainer.style.display = 'block';
        } else {
            newTypeContainer.style.display = 'none';
        }
    });
    
    // Trigger change event on page load to handle initial state
    document.addEventListener('DOMContentLoaded', function() {
        const userType = document.getElementById('user_type');
        if (userType.value === 'new_type') {
            document.getElementById('new_type_container').style.display = 'block';
        }
    });
    
    // Form submission handler for new type
    document.querySelector('form').addEventListener('submit', function(e) {
        const userType = document.getElementById('user_type');
        const newUserType = document.getElementById('new_user_type');
        
        if (userType.value === 'new_type' && newUserType.value.trim() === '') {
            e.preventDefault();
            alert('Please enter a name for the new user type');
            newUserType.focus();
        } else if (userType.value === 'new_type') {
            userType.value = newUserType.value.trim().toLowerCase().replace(/ /g, '_');
        }
    });
</script>
@endpush
@endsection
