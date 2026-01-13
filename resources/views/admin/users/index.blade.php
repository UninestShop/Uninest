@extends('admin.layouts.app', ['title' => 'Users Management'])

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Users</h5>
            {{-- <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Add New User
            </a> --}}
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Users Table -->
            <div class="table-responsive">
                <table id="users-table" class="table table-hover display nowrap w-100">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $k=> $user)
                            <tr>
                                <td>{!!$k+1!!}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td data-sort="{{ $user->created_at->timestamp }}">{{ $user->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.users.edit', $user->slug) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->status != 'approved')
                                            <button type="button" class="btn btn-outline-success" 
                                                    onclick="confirmStatusChange('{{ $user->slug }}', 'approved', 'approve')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="confirmStatusChange('{{ $user->slug }}', 'rejected', 'reject')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete('{{ $user->slug }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $user->slug }}" 
                                          action="{{ route('admin.users.destroy', $user->slug) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    
                                    <form id="status-form-{{ $user->slug }}" 
                                          action="{{ route('admin.users.update-status', $user->slug) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" id="status-value-{{ $user->slug }}">
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Status Change Confirmation Modal -->
<div class="modal fade" id="statusConfirmModal" tabindex="-1" aria-labelledby="statusConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusConfirmModalLabel">Confirm Status Change</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="statusConfirmMessage">
        Are you sure you want to change this user's status?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmStatusBtn">Confirm</button>
      </div>
    </div>
  </div>
</div>

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
<style>
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }
    .badge {
        font-size: 0.8rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
<!-- Make sure Bootstrap JS is properly loaded -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTables
        const table = $('#users-table').DataTable({
            responsive: true,
            processing: true,
            ordering: true,
            paging: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 100]],
            // order: [[0, 'desc']],
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: 1 },
                { responsivePriority: 3, targets: 5 },
                { orderable: false, targets: 5 }
            ]
        });
        
        // Delete confirmation
        window.confirmDelete = function(slug) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                document.getElementById(`delete-form-${slug}`).submit();
            }
        };
        
        // Variables to store current status change info
        let currentSlug = '';
        let currentStatus = '';
        
        // Create a Bootstrap 5 modal instance
        let statusModal;
        
        // Initialize the modal when document is ready
        try {
            statusModal = new bootstrap.Modal(document.getElementById('statusConfirmModal'));
        } catch (error) {
            console.error("Error initializing modal:", error);
        }
        
        // Status change confirmation with custom modal
        window.confirmStatusChange = function(slug, statusValue, action) {
            // Store values for use when confirmed
            currentSlug = slug;
            currentStatus = statusValue;
            
            // Update confirmation message
            $('#statusConfirmMessage').text(`Are you sure you want to ${action} this user?`);
            
            try {
                // Show the modal using Bootstrap 5 method
                statusModal.show();
            } catch (error) {
                console.error("Error showing modal:", error);
                // Fallback to direct form submission if modal fails
                if (confirm(`Are you sure you want to ${action} this user?`)) {
                    document.getElementById(`status-value-${slug}`).value = statusValue;
                    document.getElementById(`status-form-${slug}`).submit();
                }
            }
        };
        
        // Handle confirm button click
        $('#confirmStatusBtn').on('click', function() {
            // Change button state to loading
            const $btn = $(this);
            const originalText = $btn.text();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            
            // Get the form data
            const form = document.getElementById(`status-form-${currentSlug}`);
            document.getElementById(`status-value-${currentSlug}`).value = currentStatus;
            
            try {
                // Hide the modal using Bootstrap 5 method
                statusModal.hide();
            } catch (error) {
                console.error("Error hiding modal:", error);
            }
            
            // Submit form directly (no AJAX) for simpler page refresh
            form.submit();
        });

        // Auto-dismiss alert messages after 5 seconds
        setTimeout(function() {
            $('.alert-success').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    });
</script>
@endpush
@endsection
