@extends('admin.layouts.app')

@section('content')
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <a href="{{ route('admin.products.index') }}" style="text-decoration: none;">
            <div class="card bg-primary text-white" style="cursor: pointer;">
            <div class="card-body">
                <h5 class="card-title">Total Products</h5>
                <h2>{{ $totalProducts }}</h2>
                <p class="mb-0">{{ $newProductsThisWeek }} new this week</p>
            </div>
            </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.users.index') }}" style="text-decoration: none;">
            <div class="card bg-success text-white" style="cursor: pointer;">
                <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2>{{ $totalUsers }}</h2>
                <p class="mb-0">{{ $newUsersThisWeek }} new this week</p>
                </div>
            </div>
            </a>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white" data-bs-toggle="modal" data-bs-target="#productLimitModal" style="cursor: pointer;">
            <div class="card-body">
                <h5 class="card-title">Product Limit</h5>
                <h2>{{ $productLimit ?? 0 }}</h2>
                <p class="mb-0">Click to manage</p>
            </div>
            </div>
        </div>

        <!-- Product Limit Modal -->
        <div class="modal fade" id="productLimitModal" tabindex="-1" aria-labelledby="productLimitModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="productLimitModalLabel">Manage Product Limit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form action="{{ route('admin.settings.productLimit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                    <label for="productLimit" class="form-label">Product Limit</label>
                    <input type="number" class="form-control" id="productLimit" name="product_limit" value="{{ $productLimit ?? 0 }}" min="0">
                    <small class="form-text text-muted">Set to 0 for unlimited products</small>
                    </div>
                    <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </div>

   {{-- <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Transactions</h5>
                    <h2>{{ \App\Models\Transaction::count() }}</h2>
                    <p class="mb-0">${{ number_format(\App\Models\Transaction::sum('amount'), 2) }} total value</p>
                </div>
            </div>
        </div> --}}
     
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    {{-- <h5 class="card-title">Pending Reviews</h5> --}}
                    <h5>flagged messages</h5>
                    {{-- <h2>{{ \App\Models\Product::where('status', 'pending')->count() }}</h2> --}}
                    <h2>{{ $flaggedMessages }} </h2>
                    <p class="mb-0"> flagged messages</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Products Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="productStatusChart"></canvas>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Weekly Transactions</h5>
                </div>
                <div class="card-body">
                    <canvas id="transactionsChart"></canvas>
                </div>
            </div>
        </div> --}}
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Product Status Chart
    const productCtx = document.getElementById('productStatusChart').getContext('2d');
    new Chart(productCtx, {
        type: 'pie',
        data: {
            labels: ['Approved', 'Rejected', 'Flagged'],
            datasets: [{
                data: [
                    {{ $approvedProducts }},
                    {{ $rejectedProducts }},
                    {{ $flaggedProducts }}
                ],
                backgroundColor: ['#28a745', '#dc3545', '#ffc107']
            }]
        }
    });

    // Transactions Chart
    const transCtx = document.getElementById('transactionsChart').getContext('2d');
    new Chart(transCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($lastWeekDates ?? []) !!},
            datasets: [{
                label: 'Transactions',
                data: {!! json_encode($lastWeekTransactions ?? []) !!},
                borderColor: '#007bff',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection
