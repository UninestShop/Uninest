@extends('admin.layouts.app')

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h3 class="m-0 font-weight-bold text-primary">Transactions Management</h3>
            <a href="{{ route('admin.transactions.issues') }}" class="btn btn-warning">
                <i class="fas fa-exclamation-triangle"></i> View Issues
            </a>
        </div>
        <div class="card-body">
            <table class="table table-hover" id="transactionsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Issue</th>
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
    $('#transactionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.transactions.index") }}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'user.name', name: 'user.name'},
            {data: 'product.name', name: 'product.name'},
            {
                data: 'amount',
                name: 'amount',
                render: function(data) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            {
                data: 'status',
                name: 'status',
                render: function(data) {
                    return `<span class="status-badge status-${data.toLowerCase()}">${data}</span>`;
                }
            },
            {data: 'type', name: 'type'},
            {
                data: 'issue_reported',
                name: 'issue_reported',
                render: function(data) {
                    return data ? 
                        '<span class="badge bg-danger">Yes</span>' : 
                        '<span class="badge bg-success">No</span>';
                }
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <a href="/admin/transactions/${row.id}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    `;
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true,
        dom: '<"d-flex justify-content-between align-items-center mb-4"lf>rtip',
        language: {
            search: "",
            searchPlaceholder: "Search...",
            lengthMenu: "_MENU_ per page"
        },
    });
});
</script>
@endpush
@endsection
