@extends('layouts.admin')

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container">
    <div class="card">
        <div class="card-header">Admin Settings</div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <div class="form-group">
                    <label for="max_products_per_user">Maximum Products Per User</label>
                    <input type="number" id="max_products_per_user" name="max_products_per_user" 
                           value="{{ $settings['max_products_per_user'] ?? 10 }}" class="form-control">
                    @error('max_products_per_user')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection
