@extends('admin.layouts.app', ['title' => 'Message Monitoring'])

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <!-- Message Monitoring Dashboard -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-0">Total Messages</h6>
                            <h2 class="fw-bold mb-0">{{ App\Models\Chat::count() }}</h2>
                        </div>
                        <div class="bg-white p-3 rounded-circle">
                            <i class="fas fa-comment text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-0">Flagged Messages</h6>
                            <h2 class="fw-bold mb-0">{{ App\Models\ChatFlag::count() }}</h2>
                        </div>
                        <div class="bg-white p-3 rounded-circle">
                            <i class="fas fa-flag text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-0">Active Conversations</h6>
                            <h2 class="fw-bold mb-0">{{ App\Models\Chat::distinct('product_id')->count('product_id') }}</h2>
                        </div>
                        <div class="bg-white p-3 rounded-circle">
                            <i class="fas fa-comments text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            {{-- <div class="col-md-3">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white mb-0">Blocked Users</h6>
                                <h2 class="fw-bold mb-0">{{ App\Models\User::where('is_blocked', true)->count() }}</h2>
                            </div>
                            <div class="bg-white p-3 rounded-circle">
                                <i class="fas fa-user-slash text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
    </div>

    <!-- Messages List -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Message Monitoring</h5>
            <a href="{{ route('admin.messages.history') }}" class="btn btn-outline-primary">
                <i class="fas fa-history me-1"></i> View Complete Chat History
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="table-responsive">
                <table id="messages-table" class="table table-hover display nowrap w-100">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Product</th>
                            <th>Message</th>
                            <th>Flags</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
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

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
    .highlight-inappropriate {
        background-color: rgba(255, 0, 0, 0.1);
        padding: 2px 4px;
        border-radius: 4px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
    $(document).ready(function() {
        console.log("Initializing DataTables");
        
        // Initialize DataTables with debugging
        try {
            const table = $('#messages-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.messages.index') }}",
                    type: 'GET',
                    error: function(xhr, error, thrown) {
                        console.error('DataTables AJAX error:', error, thrown);
                        console.log('Response:', xhr.responseText);
                    }
                },
                columns: [
                    {
                        data: null, 
                        name: 'id',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: 'sender_name', name: 'sender.name'},
                    {data: 'receiver_name', name: 'receiver.name'},
                    {data: 'product_name', name: 'product.name'},
                    {
                        data: 'message', 
                        name: 'message',
                        render: function(data) {
                            if (!data) return '';
                            
                            // Basic inappropriate word highlighting
                            const inappropriateWords = ['scam', 'fraud', 'illegal', 'drugs', 'xxx'];
                            let message = data;
                            
                            inappropriateWords.forEach(word => {
                                const regex = new RegExp('\\b' + word + '\\b', 'gi');
                                message = message.replace(regex, '<span class="highlight-inappropriate">$&</span>');
                            });
                            
                            return message;
                        }
                    },
                    {data: 'flags', name: 'flags', render: function(data) { return data || 0; }},
                    {
                        data: 'created_at', 
                        name: 'created_at',
                        render: function(data) {
                            return data ? moment(data).format('MMM D, YYYY HH:mm') : '';
                        }
                    },
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ],
                order: [[0, 'desc']]
            });
            
            console.log("DataTables initialized successfully");
        } catch(e) {
            console.error("DataTables initialization error:", e);
        }
        
        // Handle Flag Message Modal
        window.openFlagModal = function(id, message) {
            try {
                const form = document.getElementById('flag-message-form');
                form.action = `/admin/messages/${id}/review`;
                
                document.getElementById('flag-message-content').textContent = message;
                $('#flagMessageModal').modal('show');
            } catch(e) {
                console.error("Error in openFlagModal:", e);
            }
        };
        
        // Show/hide "other reason" field
        const flagReason = document.getElementById('flag-reason');
        if (flagReason) {
            flagReason.addEventListener('change', function() {
                const otherContainer = document.getElementById('other-reason-container');
                if (otherContainer) {
                    otherContainer.style.display = this.value === 'Other' ? 'block' : 'none';
                }
            });
        }
        
        // Handle form submission for flagging
        const flagMessageForm = document.getElementById('flag-message-form');
        if (flagMessageForm) {
            flagMessageForm.addEventListener('submit', function(e) {
                const reason = document.getElementById('flag-reason').value;
                const otherReason = document.getElementById('other-reason').value;
                
                if (reason === 'Other' && !otherReason.trim()) {
                    e.preventDefault();
                    alert('Please specify the reason for flagging this message.');
                } else if (reason === 'Other') {
                    // Set the custom reason
                    const reasonInput = document.createElement('input');
                    reasonInput.type = 'hidden';
                    reasonInput.name = 'reason';
                    reasonInput.value = otherReason;
                    this.appendChild(reasonInput);
                }
            });
        }
    });
</script>
@endpush
@endsection
