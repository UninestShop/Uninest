@extends('admin.layouts.app')

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>User Permissions Management</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="userPermissionsTable" width="100%">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Current Roles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#userPermissionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.users.permissions") }}',
            type: 'GET'
        },
        columns: [
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {
                data: 'roles',
                name: 'roles',
                render: function(data) {
                    return data.map(role => role.name).join(', ');
                }
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <a href="/admin/users/${row.id}/permissions" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit Permissions
                        </a>`;
                }
            }
        ]
    });
});
</script>
@endpush
@endsection
