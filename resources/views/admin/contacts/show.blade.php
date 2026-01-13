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
                    <h3 class="card-title">Contact Message</h3>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-secondary float-right">Back to List</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Sender Information</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $contact->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $contact->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>User Account</th>
                                        <td>
                                            @if($contact->user)
                                                <a href="{{ route('admin.users.edit', $contact->user) }}">
                                                    {{ $contact->user->name }} (ID: {{ $contact->user->id }})
                                                </a>
                                            @else
                                                <span class="text-muted">Guest message</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($contact->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($contact->status == 'read')
                                                <span class="badge badge-info">Read</span>
                                            @elseif($contact->status == 'responded')
                                                <span class="badge badge-success">Responded</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td>{{ $contact->created_at->format('M d, Y h:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Message</h5>
                            <div class="card">
                                <div class="card-header bg-light">
                                    <strong>Subject: {{ $contact->subject }}</strong>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $contact->message }}</p>
                                </div>
                            </div>
                            
                            @if($contact->status != 'responded')
                                <form action="{{ route('admin.contacts.respond', $contact) }}" method="POST" class="mt-3">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Mark as Responded
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
