@extends('admin.layouts.app')

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Categories</h5>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Category
            </a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table class="table table-hover" id="categoriesTable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        {{-- <th>Status</th> --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $key=> $category)
                    <tr>
                        <td>{!! $key+1 !!}</td>
                        <td>{{ $category->name }}</td>
                        {{-- <td>
                            <span class="badge bg-{{ $category->status ? 'success' : 'danger' }}">
                                {{ $category->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td> --}}
                        <td>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#categoriesTable').DataTable({
            // "order": [[0, "desc"]]
        });
    });
</script>
@endpush
