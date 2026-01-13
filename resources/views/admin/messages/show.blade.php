@extends('admin.layouts.app', ['title' => 'View Conversation'])

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Conversation Details</h5>
                    <p class="text-muted mb-0">
                        Regarding: <strong>{{ $product->name }}</strong>
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Messages
                    </a>
                </div>
            </div>
        </div>
        {{-- @dd($user1, $user2) --}}
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">User 1</h6>
                            <div class="d-flex align-items-center mb-3">
                                @if($user1->profile_picture)
                                    <img src="{{ asset('storage/'.$user1->profile_picture) }}" class="rounded-circle me-2" width="40" height="40" alt="{{ $user1->name }}">
                                @else
                                    <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white">{{ substr($user1->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $user1->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $user1->email }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    {{-- <p class="small mb-0">
                                        <strong>User Type:</strong> {{ ucfirst($user1->user_type ?? 'Standard') }}
                                    </p> --}}
                                    <p class="small mb-0">
                                        <strong>Joined:</strong> {{ $user1->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                                {{-- <div>
                                    @if($user1->is_blocked)
                                        <span class="badge bg-danger">Blocked User</span>
                                    @else
                                        <form action="{{ route('admin.users.block', $user1) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Block User</button>
                                        </form>
                                    @endif
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">User 2</h6>
                            <div class="d-flex align-items-center mb-3">
                                @if($user2->profile_picture)
                                    <img src="{{ asset('storage/'.$user2->profile_picture) }}" class="rounded-circle me-2" width="40" height="40" alt="{{ $user2->name }}">
                                @else
                                    <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white">{{ substr($user2->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $user2->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $user2->email }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    {{-- <p class="small mb-0">
                                        <strong>User Type:</strong> {{ ucfirst($user2->user_type ?? 'Standard') }}
                                    </p> --}}
                                    <p class="small mb-0">
                                        <strong>Joined:</strong> {{ $user2->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                                {{-- <div>
                                    @if($user2->is_blocked)
                                        <span class="badge bg-danger">Blocked User</span>
                                    @else
                                        <form action="{{ route('admin.users.block', $user2) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Block User</button>
                                        </form>
                                    @endif
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Conversation History</h6>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @foreach($messages as $message)
                        <div class="mb-3 {{ $message->sender_id == $user1->id ? '' : 'text-end' }}">
                            <div class="d-inline-block {{ $message->sender_id == $user1->id ? 'bg-light' : 'bg-primary text-white' }} p-3 rounded-3" style="max-width: 80%;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-bold">{{ $message->sender_id == $user1->id ? $user1->name : $user2->name }}</span>
                                    <span class="small ms-2">{{ $message->created_at->format('M d, H:i') }}</span>
                                </div>
                                <div>{{ $message->message }}</div>
                            </div>
                            
                            @if($message->flags->count() > 0)
                                <div class="mt-1">
                                    <span class="badge bg-danger">
                                        <i class="fas fa-flag me-1"></i> Flagged: {{ $message->flags->first()->reason }}
                                    </span>
                                </div>
                            @else
                                <div class="mt-1">
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            onclick="openFlagModal('{{ $message->id }}', '{{ addslashes($message->message) }}')">
                                        <i class="fas fa-flag me-1"></i> Flag
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Flag Message Modal -->
<div class="modal fade" id="flagMessageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="flag-message-form" action="" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Flag Inappropriate Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Message Content:</label>
                        <p id="flag-message-content" class="p-2 bg-light rounded"></p>
                    </div>
                    <div class="mb-3">
                        <label for="flag-reason" class="form-label">Reason for Flagging:</label>
                        <select class="form-select" id="flag-reason" name="reason" required>
                            <option value="">Select a reason</option>
                            <option value="Harassment">Harassment</option>
                            <option value="Inappropriate content">Inappropriate content</option>
                            <option value="Spam">Spam</option>
                            <option value="Scam attempt">Scam attempt</option>
                            <option value="Personal information sharing">Personal information sharing</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3" id="other-reason-container" style="display: none;">
                        <label for="other-reason" class="form-label">Specify Reason:</label>
                        <input type="text" class="form-control" id="other-reason" name="other_reason">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" value="flag">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Flag Message</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Scroll to bottom of chat messages on page load
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.querySelector('.card-body[style*="overflow-y: auto"]');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
    
    // Handle Flag Message Modal
    window.openFlagModal = function(id, message) {
        const form = document.getElementById('flag-message-form');
        form.action = `/admin/messages/${id}/review`;
        
        document.getElementById('flag-message-content').textContent = message;
        $('#flagMessageModal').modal('show');
    };
    
    // Show/hide "other reason" field
    document.getElementById('flag-reason').addEventListener('change', function() {
        const otherContainer = document.getElementById('other-reason-container');
        otherContainer.style.display = this.value === 'Other' ? 'block' : 'none';
    });
</script>
@endpush
@endsection
