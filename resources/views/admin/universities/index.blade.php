@extends('admin.layouts.app', ['title' => 'Universities'])

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">Universities</h1>
            <p class="mb-0">Manage all universities in the system</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.universities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add University
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
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Universities</h6>
            </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="universities-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            {{-- <th>Status</th> --}}
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#universities-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('admin.universities.data') }}",
                type: 'GET',
                error: function(xhr, error, thrown) {
                    console.error('DataTables Ajax error:', xhr.responseText);
                    alert('Error loading data. See console for details.');
                }
            },
            columns: [
                { 
                    data: null, 
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'name' },
                // { data: 'domains' },
                { data: 'actions', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush
