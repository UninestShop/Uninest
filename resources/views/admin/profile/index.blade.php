@extends('admin.layouts.app', ['title' => 'My Profile'])

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="row">
        <!-- Profile Information Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(Auth::user()->image)
                            <img src="{{ asset(Auth::user()->image) }}" alt="{{ Auth::user()->name }}" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                                <span class="text-white" style="font-size: 60px;">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <h4>{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-2">{{ Auth::user()->email }}</p>
                    
                    {{-- <div class="d-flex justify-content-center gap-2 mb-3">
                        @forelse(Auth::user()->roles ?? [] as $role)
                            <span class="badge bg-info">{{ $role->name }}</span>
                        @empty
                            <span class="badge bg-secondary">No Roles Assigned</span>
                        @endforelse
                    </div> --}}
                    
                    <p class="small text-muted">
                        <i class="fas fa-clock me-1"></i> Member since: {{ Auth::user()->created_at->format('M d, Y') }}
                    </p>
                    
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#avatarModal">
                        <i class="fas fa-camera me-1"></i> Change Avatar
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Profile Edit Card -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Edit Profile</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" disabled value="{{ old('email', Auth::user()->email) }} ">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="mb-3">Change Password</h5>
                        <p class="text-muted small mb-3">Leave password fields empty if you don't want to change it</p>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Activity Log Card -->
        {{-- <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Activity</th>
                                    <th>IP Address</th>
                                    <th>Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- You would fetch actual activity data in your controller -->
                                <tr>
                                    <td><i class="fas fa-sign-in-alt text-success me-2"></i> Logged in</td>
                                    <td>192.168.1.1</td>
                                    <td>{{ now()->subHours(1)->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user-edit text-info me-2"></i> Updated profile</td>
                                    <td>192.168.1.1</td>
                                    <td>{{ now()->subDays(2)->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-sign-out-alt text-warning me-2"></i> Logged out</td>
                                    <td>192.168.1.1</td>
                                    <td>{{ now()->subDays(2)->subHours(2)->format('M d, Y H:i:s') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>

<!-- Avatar Change Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">Change Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Select New Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        <div class="form-text">Recommended size: 300x300 pixels</div>
                    </div>
                    
                    <div id="avatar-preview" class="text-center my-3 d-none">
                        <img src="" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload & Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Avatar preview
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            const preview = document.getElementById('avatar-preview');
            
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('d-none');
            };
            
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
