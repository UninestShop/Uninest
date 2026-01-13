@extends('admin.layouts.app', ['title' => 'Products'])

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Products List</h4>
            <div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary filter-btn" data-status="all">All</button>
                    {{-- <button type="button" class="btn btn-outline-secondary filter-btn" data-status="pending">Pending</button> --}}
                    <button type="button" class="btn btn-outline-secondary filter-btn" data-status="approved">Approved</button>
                    <button type="button" class="btn btn-outline-secondary filter-btn" data-status="rejected">Rejected</button>
                    <button type="button" class="btn btn-outline-secondary filter-btn" data-status="flagged">Flagged</button>
                </div>
                {{-- <a href="{{ route('admin.products.create') }}" class="btn btn-primary ms-2">
                    <i class="fas fa-plus-circle me-1"></i> Add Product
                </a> --}}
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <table class="table table-bordered" id="productsTable" width="100%">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>User</th>
                        {{-- <th>MRP</th> --}}
                        <th>Price</th>
                        <th>Status</th>
                        <th width="200">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge {
        padding: 0.5em 0.75em;
        font-size: 0.75rem;
    }
    .filter-btn.active {
        background-color: #0d6efd;
        color: white;
    }
    .action-btn {
        margin-right: 3px;
    }
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    /* Status badge styles */
    .badge {
        font-size: 0.75rem;
    }
    
    /* Status action highlighting */
    .dropdown-item.active {
        background-color: #e9ecef;
        color: #000;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#productsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
            ordering: true,
            paging: true,
        ajax: {
            url: '{{ route("admin.products.index") }}',
            type: 'GET',
            data: function(d) {
                d.status = $('.filter-btn.active').data('status');
            }
        },
        columns: [
            {
                data: 'id',
                name: 'id',
                render: function(data, type, row, meta) {
                    // Calculate serial number based on page number and row index
                    return meta.settings._iDisplayStart + meta.row + 1;
                }
            },
            {data: 'name', name: 'name'},
            {data: 'user.name', name: 'user.name'},
            // {
            //     data: 'mrp',
            //     name: 'mrp',
            //     render: function(data) {
            //         return data ? '$' + parseFloat(data).toFixed(2) : '-';
            //     }
            // },
            {
                data: 'selling_price',
                name: 'selling_price',
                render: function(data) {
                    return data ? '$' + parseFloat(data).toFixed(2) : '-';
                }
            },
            {
                data: 'status',
                name: 'status',
                render: function(data) {
                    let badgeClass = '';
                    
                    switch(data) {
                        case 'pending':
                            badgeClass = 'bg-secondary';
                            break;
                        case 'approved':
                            badgeClass = 'bg-success';
                            break;
                        case 'rejected':
                            badgeClass = 'bg-danger';
                            break;
                        case 'flagged':
                            badgeClass = 'bg-warning';
                            break;
                        default:
                            badgeClass = 'bg-secondary';
                    }
                    
                    return `<span class="badge ${badgeClass}">${data ? data.charAt(0).toUpperCase() + data.slice(1) : 'Pending'}</span>`;
                }
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        // order: [[0, 'desc']]
    });
    
    // Status filter functionality
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        table.ajax.reload();
    });
    
    // Set "All" as the default active filter
    $('.filter-btn[data-status="all"]').addClass('active');
    
    // Delete confirmation
    window.confirmDelete = function(slug) {
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            document.getElementById(`delete-form-${slug}`).submit();
        }
    };
    
    // Function to handle product status changes via AJAX - with real-time UI updates
    window.changeProductStatus = function(productId, action) {
        // Show loading state
        const loadingOverlay = $('<div class="loading-overlay">' +
                               '<div class="spinner-border text-primary" role="status">' +
                               '<span class="visually-hidden">Loading...</span></div></div>');
        $('body').append(loadingOverlay);
        
        // Set status-specific styling before AJAX call for faster UI response
        let bgClass, iconClass, actionText, statusClass, statusText;
        
        switch(action) {
            case 'approve':
                bgClass = 'bg-success';
                iconClass = 'fas fa-check-circle';
                actionText = 'approved';
                statusClass = 'bg-success';
                statusText = 'Approved';
                break;
            case 'reject':
                bgClass = 'bg-danger';
                iconClass = 'fas fa-times-circle';
                actionText = 'rejected';
                statusClass = 'bg-danger';
                statusText = 'Rejected';
                break;
            case 'flag':
                bgClass = 'bg-warning text-dark';
                iconClass = 'fas fa-flag';
                actionText = 'flagged';
                statusClass = 'bg-warning';
                statusText = 'Flagged';
                break;
            default:
                bgClass = 'bg-info';
                iconClass = 'fas fa-info-circle';
                actionText = 'updated';
                statusClass = 'bg-secondary';
                statusText = 'Updated';
        }
        
        // Step 1: Immediately update the row status
        updateRowStatusImmediately(productId, statusClass, statusText);
        
        // Step 2: Immediately show toast notification - don't wait for AJAX
        showStatusNotification(bgClass, iconClass, actionText);
        
        // Step 3: Send AJAX request for server persistence
        $.ajax({
            url: `{{ url('/admin/products') }}/${productId}/${action}`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            cache: false,
            success: function(response) {
                console.log("Success response:", response);
                
                // Remove auto table reload - we've already updated the UI
                // setTimeout(function() {
                //     table.ajax.reload(null, false);
                // }, 1000);
                
                // Update just the actions cell based on new status
                updateActionCellForStatus(productId, action);
            },
            error: function(xhr, status, error) {
                console.error("Error changing product status:", error);
                console.log("Response:", xhr.responseText);
                
                // Show error message
                const errorMessage = xhr.responseJSON?.message || "Failed to update product status. Please try again.";
                showErrorNotification(errorMessage);
                
                // Refresh the table to reset any incorrect UI updates
                table.ajax.reload(null, false);
            },
            complete: function() {
                // Remove loading overlay
                loadingOverlay.remove();
            }
        });
    };
    
    // New function to update the actions cell based on status
    function updateActionCellForStatus(productId, newStatus) {
        $('#productsTable tbody tr').each(function() {
            const rowData = table.row(this).data();
            if (rowData && rowData.id == productId) {
                // Get the actions cell (last column)
                const actionsCell = $(this).find('td:last-child');
                
                // Find all the status change buttons
                const buttons = actionsCell.find('.dropdown-item');
                
                // Update button visibility based on new status
                buttons.each(function() {
                    const btnAction = $(this).data('action');
                    
                    // Hide the button for the current status action (already performed)
                    if (btnAction === newStatus) {
                        $(this).addClass('d-none');
                    } else {
                        $(this).removeClass('d-none');
                    }
                });
                
                return false; // Break the loop once found
            }
        });
    }
    
    // Function to immediately update row status without waiting for server response
    function updateRowStatusImmediately(productId, statusClass, statusText) {
        // Find the row with matching product ID
        $('#productsTable tbody tr').each(function() {
            const rowData = table.row(this).data();
            if (rowData && rowData.id == productId) {
                // Get the current serial number from the first cell
                const currentSerialNumber = $(this).find('td:first-child').text();
                
                // Update the row data object with new status to keep it consistent
                rowData.status = statusText.toLowerCase();
                table.row(this).data(rowData);
                
                // Restore the serial number in the first cell
                $(this).find('td:first-child').text(currentSerialNumber);
                
                // Get the status cell (5th column)
                const statusCell = $(this).find('td:eq(4)'); // Changed to index 4 (zero-based)
                
                // Update the badge immediately with animation
                const newBadge = $(`<span class="badge ${statusClass}" 
                                    style="transform: scale(1.2); transition: transform 0.3s">${statusText}</span>`);
                statusCell.html(newBadge);
                
                // Return to normal scale after animation
                setTimeout(() => {
                    newBadge.css('transform', 'scale(1)');
                }, 50);
                
                // Flash the row to indicate change
                $(this).addClass('table-active');
                setTimeout(() => $(this).removeClass('table-active'), 800);
                
                return false; // Break the loop once found
            }
        });
    }
    
    // Function to show status notification toast immediately
    function showStatusNotification(bgClass, iconClass, actionText) {
        // Remove any existing toasts first
        $('.status-toast-container').remove();
        
        // Create a unique ID for this toast
        const toastId = 'statusToast-' + Date.now();
        
        // Create toast container - changed from bottom-0 to top-0 for top positioning
        const toastContainer = $('<div class="position-fixed top-0 end-0 p-3 status-toast-container" style="z-index: 11"></div>');
        
        // Create toast with status-specific styling and animation
        // Changed translateY from positive to negative for top animation
        const toast = $(`<div id="${toastId}" class="toast align-items-center ${bgClass} border-0" 
                      role="alert" aria-live="assertive" aria-atomic="true" 
                      style="transform: translateY(-20px); opacity: 0; transition: all 0.3s ease-out">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="${iconClass} me-2"></i>
                    <strong>Status Updated:</strong> Product has been ${actionText} successfully.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>`);
        
        // Add toast to container and container to body
        toastContainer.append(toast);
        $('body').append(toastContainer);
        
        // Animate the toast in
        setTimeout(() => {
            toast.css({
                'transform': 'translateY(0)',
                'opacity': 1
            });
        }, 10);
        
        // Show the toast using Bootstrap
        const toastEl = document.getElementById(toastId);
        if (toastEl) {
            const bsToast = new bootstrap.Toast(toastEl, {
                delay: 5000
            });
            bsToast.show();
        }
    }
    
    // Function to show error notification
    function showErrorNotification(errorMessage) {
        // Remove any existing error toasts
        $('#errorToastContainer').remove();
        
        // Changed from bottom-0 to top-0 for top positioning
        const errorToastContainer = $('<div id="errorToastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 11"></div>');
        
        // Changed translateY from positive to negative for top animation
        const errorToast = $(`<div id="errorToast" class="toast align-items-center text-white bg-danger border-0" 
                           role="alert" aria-live="assertive" aria-atomic="true"
                           style="transform: translateY(-20px); opacity: 0; transition: all 0.3s ease-out">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Error:</strong> ${errorMessage}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>`);
        
        errorToastContainer.append(errorToast);
        $('body').append(errorToastContainer);
        
        // Animate the toast in
        setTimeout(() => {
            errorToast.css({
                'transform': 'translateY(0)',
                'opacity': 1
            });
        }, 10);
        
        const errorToastEl = document.getElementById('errorToast');
        if (errorToastEl) {
            const bsErrorToast = new bootstrap.Toast(errorToastEl);
            bsErrorToast.show();
        }
    }
});
</script>
@endpush
@endsection
