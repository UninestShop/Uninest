@extends('admin.layouts.app', ['title' => 'Add Product'])

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Create New Product</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.products.form')
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Create Product</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @stack('form-scripts')
@endpush
