@extends('admin.layouts.app')

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card contact-form">
                <div class="card-header">
                    <h3 class="card-title">Contact Messages</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $contact)
                                <tr>
                                    <td>{{ $contact->id }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->subject }}</td>
                                    <td>
                                        @if($contact->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($contact->status == 'read')
                                            <span class="badge badge-info">Read</span>
                                        @elseif($contact->status == 'responded')
                                            <span class="badge badge-success">Responded</span>
                                        @endif
                                    </td>
                                    <td>{{ $contact->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No contact messages found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $contacts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
