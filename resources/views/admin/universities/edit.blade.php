@extends('admin.layouts.app', ['title' => 'Edit University'])

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">Edit University</h1>
            <p class="mb-0">Update university information</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Universities
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">University Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.universities.update', $university->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $university->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="domains" class="form-label">Domains <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('domains') is-invalid @enderror" id="domains" name="domains" 
                               value="{{ old('domains', is_string($university->domains) && json_decode($university->domains) ? implode(', ', json_decode($university->domains, true)) : $university->domains) }}" required>
                        <small class="form-text text-muted">Enter domains separated by commas</small>
                        @error('domains')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    
                    {{-- <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', $university->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $university->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}
                </div>
                
                {{-- <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="contact_email" class="form-label">Contact Email</label>
                        <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $university->contact_email) }}">
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="mobile_number" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $university->mobile_number) }}">
                        @error('mobile_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update University</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
