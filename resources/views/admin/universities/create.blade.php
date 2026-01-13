@extends('admin.layouts.app', ['title' => 'Add University'])

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">Add University</h1>
            <p class="mb-0">Create a new university record</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Universities
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">University Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.universities.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">University Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                   <div class="mb-3">
                    <label for="name" class="form-label">Domains<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="domains" name="domains" value="{{ old('domains') }}" required>
                    @error('domains')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                {{-- <div class="mb-3">
                    <label for="contact_email" class="form-label">Contact Email</label>
                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
                    @error('contact_email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}

                {{-- <div class="mb-3">
                    <label for="mobile_number" class="form-label">Contact Phone</label>
                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}">
                    @error('mobile_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}

                {{-- <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save University</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
