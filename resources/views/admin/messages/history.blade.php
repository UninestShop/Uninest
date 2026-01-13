@extends('admin.layouts.app', ['title' => 'Chat History'])

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Message History</h5>
            <div>
                {{-- <button type="button" class="btn btn-outline-success me-2" id="export-btn">
                    <i class="fas fa-file-csv me-1"></i> Export to CSV
                </button> --}}
                <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-shield-alt me-1"></i> Return to Monitoring
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="table-responsive">
                <table id="chat-history-table" class="table table-hover display nowrap w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Product</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
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
    
    <!-- Analytics Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Message Volume</h5>
                </div>
                <div class="card-body">
                    <canvas id="messageVolumeChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Flag Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="flagDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
    $(document).ready(function() {
        console.log("Initializing Chat History page...");
        
        try {
            // Initialize Select2
            $('.form-select').select2({
                width: '100%'
            });
            console.log("Select2 initialized");
            
            // Store the filter values
            let filterValues = {
                sender_id: $('#sender_id').val(),
                receiver_id: $('#receiver_id').val(),
                product_id: $('#product_id').val(),
                flagged: $('#flagged').val(),
                date_from: $('#date_from').val(),
                date_to: $('#date_to').val(),
                search_text: $('#search_text').val()
            };
            
            console.log("Initial filter values:", filterValues);
            
            // Initialize DataTables with AJAX and error handling
            const table = $('#chat-history-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.messages.history') }}",
                    type: 'GET',
                    data: function(d) {
                        // Add form filters to the request
                        console.log("DataTables requesting data with filters:", filterValues);
                        return $.extend({}, d, filterValues);
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTables error:', error, thrown);
                        console.log('Server response:', xhr.responseText);
                        alert('Error loading chat history data. Check console for details.');
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'sender_name', name: 'sender_name'},
                    {data: 'receiver_name', name: 'receiver_name'},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'message', name: 'message'},
                    {
                        data: 'created_at', 
                        name: 'created_at',
                        render: function(data) {
                            return data ? moment(data).format('MMM D, YYYY HH:mm') : '';
                        }
                    },
                    {
                        data: 'flags',
                        name: 'flags',
                        render: function(data, type, row) {
                            return data && data.length > 0 
                                ? '<span class="badge bg-danger">Flagged</span>' 
                                : '<span class="badge bg-success">Normal</span>';
                        }
                    },
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ],
                order: [[5, 'desc']], // Sort by date descending
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
            console.log("DataTables initialized");
            
            // Apply filters when form submitted
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                
                // Update filter values
                filterValues = {
                    sender_id: $('#sender_id').val(),
                    receiver_id: $('#receiver_id').val(),
                    product_id: $('#product_id').val(),
                    flagged: $('#flagged').val(),
                    date_from: $('#date_from').val(),
                    date_to: $('#date_to').val(),
                    search_text: $('#search_text').val()
                };
                
                console.log("Applying filters:", filterValues);
                
                // Store filters in sessionStorage for persistence
                sessionStorage.setItem('chat_history_filters', JSON.stringify(filterValues));
                
                // Reload the table with new filters
                table.ajax.reload();
                
                return false;
            });
            
            // Clear filters button
            $('#clear-filters-btn').on('click', function() {
                // Clear all form fields
                $('#sender_id').val('').trigger('change');
                $('#receiver_id').val('').trigger('change');
                $('#product_id').val('').trigger('change');
                $('#flagged').val('').trigger('change');
                $('#date_from').val('');
                $('#date_to').val('');
                $('#search_text').val('');
                
                // Reset filter values
                filterValues = {
                    sender_id: '',
                    receiver_id: '',
                    product_id: '',
                    flagged: '',
                    date_from: '',
                    date_to: '',
                    search_text: ''
                };
                
                // Clear stored filters
                sessionStorage.removeItem('chat_history_filters');
                
                // Reload the table
                table.ajax.reload();
            });
            
            // Restore filters from sessionStorage if available
            const savedFilters = sessionStorage.getItem('chat_history_filters');
            if (savedFilters) {
                try {
                    const parsedFilters = JSON.parse(savedFilters);
                    console.log("Restoring saved filters:", parsedFilters);
                    
                    // Apply saved filters to form fields
                    $('#sender_id').val(parsedFilters.sender_id).trigger('change');
                    $('#receiver_id').val(parsedFilters.receiver_id).trigger('change');
                    $('#product_id').val(parsedFilters.product_id).trigger('change');
                    $('#flagged').val(parsedFilters.flagged).trigger('change');
                    $('#date_from').val(parsedFilters.date_from);
                    $('#date_to').val(parsedFilters.date_to);
                    $('#search_text').val(parsedFilters.search_text);
                    
                    // Update filter values
                    filterValues = parsedFilters;
                    
                    // Reload the table with restored filters
                    table.ajax.reload();
                } catch(e) {
                    console.error("Error restoring saved filters:", e);
                    sessionStorage.removeItem('chat_history_filters');
                }
            }
            
            // Flag modal handler - defined globally for action buttons
            window.openFlagModal = function(id, message) {
                try {
                    const form = document.getElementById('flag-message-form');
                    if (form) {
                        form.action = `/admin/messages/${id}/review`;
                        document.getElementById('flag-message-content').textContent = message;
                        $('#flagMessageModal').modal('show');
                    } else {
                        console.error('Flag message form not found');
                    }
                } catch(e) {
                    console.error('Error in openFlagModal:', e);
                }
            };
        
            // Export to CSV
            $('#export-btn').on('click', function() {
                const queryParams = new URLSearchParams(filterValues).toString();
                window.location.href = `{{ route('admin.messages.export') }}?${queryParams}`;
            });
            
            // Initialize Charts
            try {
                const messageVolumeCtx = document.getElementById('messageVolumeChart').getContext('2d');
                const messageVolumeChart = new Chart(messageVolumeCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Message Volume',
                            data: [65, 59, 80, 81, 56, 55, 40, 44, 58, 90, 70, 85],
                            borderColor: '#0d6efd',
                            tension: 0.1,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
                
                const flagDistributionCtx = document.getElementById('flagDistributionChart').getContext('2d');
                const flagDistributionChart = new Chart(flagDistributionCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Harassment', 'Inappropriate Content', 'Spam', 'Scam Attempt', 'Personal Info', 'Other'],
                        datasets: [{
                            data: [12, 19, 3, 5, 2, 3],
                            backgroundColor: [
                                '#dc3545',
                                '#fd7e14',
                                '#ffc107',
                                '#0dcaf0',
                                '#6610f2',
                                '#6c757d'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
                console.log("Charts initialized");
            } catch (chartError) {
                console.error('Error initializing charts:', chartError);
            }
        } catch (error) {
            console.error('General initialization error:', error);
        }
    });
</script>
@endpush
@endsection
