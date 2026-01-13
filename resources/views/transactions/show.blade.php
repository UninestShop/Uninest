@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Transaction Details</h2>
            <p class="text-muted">Transaction #{{ $transaction->id }}</p>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Transactions
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Transaction Details Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Transaction Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Status:</th>
                            <td>
                                @switch($transaction->status)
                                    @case('pending')
                                        <span class="badge badge-warning">Pending</span>
                                        @break
                                    @case('completed')
                                        <span class="badge badge-success">Completed</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge badge-danger">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge badge-secondary">{{ ucfirst($transaction->status) }}</span>
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td>${{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ $transaction->created_at->format('M d, Y g:i A') }}</td>
                        </tr>
                        @if($transaction->status === 'completed' && $transaction->completed_at)
                            <tr>
                                <th>Completed:</th>
                                <td>{{ $transaction->completed_at->format('M d, Y g:i A') }}</td>
                            </tr>
                        @endif
                        @if($transaction->status === 'cancelled')
                            <tr>
                                <th>Cancelled By:</th>
                                <td>
                                    @if($transaction->cancelled_by === $transaction->buyer_id)
                                        Buyer
                                    @elseif($transaction->cancelled_by === $transaction->seller_id)
                                        Seller
                                    @else
                                        System
                                    @endif
                                </td>
                            </tr>
                            @if($transaction->cancel_reason)
                                <tr>
                                    <th>Reason:</th>
                                    <td>{{ $transaction->cancel_reason }}</td>
                                </tr>
                            @endif
                        @endif
                    </table>
                </div>
                
                @if($transaction->status === 'pending')
                    <div class="card-footer">
                        @if(auth()->id() === $transaction->seller_id)
                            <form action="{{ route('transactions.complete', $transaction) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to mark this transaction as complete?')">
                                    <i class="fas fa-check-circle"></i> Mark as Complete
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this transaction?')">
                                <i class="fas fa-times-circle"></i> Cancel Transaction
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    @if($transaction->product)
                        <div class="text-center mb-3">
                            @php
                                $photos = is_array($transaction->product->photos) 
                                    ? $transaction->product->photos 
                                    : json_decode($transaction->product->photos ?? '[]', true);
                                $firstPhoto = !empty($photos) ? $photos[0] : null;
                            @endphp
                            
                            @if($firstPhoto)
                                <img src="{{ asset($firstPhoto) }}" alt="{{ $transaction->product->name }}" class="img-fluid" style="max-height: 200px;">
                            @else
                                <div class="bg-light p-4">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <h5 class="card-title">{{ $transaction->product->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($transaction->product->description, 100) }}</p>
                        
                        <table class="table table-borderless">
                            <tr>
                                <th>Price:</th>
                                <td>${{ number_format($transaction->product->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Category:</th>
                                <td>{{ $transaction->product->category->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Condition:</th>
                                <td>{{ $transaction->product->condition }}</td>
                            </tr>
                        </table>
                        
                        <a href="{{ route('products.show', $transaction->product) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-eye"></i> View Product
                        </a>
                    @else
                        <div class="alert alert-warning">
                            Product information not available.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Details Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Seller</h6>
                        @if($transaction->seller)
                            <div class="d-flex align-items-center mb-2">
                                <div class="mr-3">
                                    @if($transaction->seller->profile_picture)
                                        <img src="{{ asset('storage/' . $transaction->seller->profile_picture) }}" 
                                             alt="{{ $transaction->seller->name }}" 
                                             class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            {{ substr($transaction->seller->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $transaction->seller->name }}</h6>
                                    <p class="text-muted mb-0 small">{{ $transaction->seller->email }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Seller information not available.</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Buyer</h6>
                        @if($transaction->buyer)
                            <div class="d-flex align-items-center mb-2">
                                <div class="mr-3">
                                    @if($transaction->buyer->profile_picture)
                                        <img src="{{ asset('storage/' . $transaction->buyer->profile_picture) }}" 
                                             alt="{{ $transaction->buyer->name }}" 
                                             class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            {{ substr($transaction->buyer->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $transaction->buyer->name }}</h6>
                                    <p class="text-muted mb-0 small">{{ $transaction->buyer->email }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Buyer information not available.</p>
                        @endif
                    </div>
                </div>
                
                @if(auth()->id() === $transaction->buyer_id && $transaction->status === 'pending')
                    <div class="card-footer">
                        <form action="{{ route('messages.start', $transaction->product_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-comment"></i> Message Seller
                            </button>
                        </form>
                    </div>
                @endif
                
                @if(auth()->id() === $transaction->seller_id && $transaction->status === 'pending')
                    <div class="card-footer">
                        <form action="{{ route('messages.start', $transaction->product_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-comment"></i> Message Buyer
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($transaction->status === 'pending')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Next Steps</h5>
                    </div>
                    <div class="card-body">
                        <ol class="pl-3">
                            <li class="mb-2">Coordinate with the {{ auth()->id() === $transaction->buyer_id ? 'seller' : 'buyer' }} to arrange a meeting.</li>
                            <li class="mb-2">Inspect the product in person before completing the transaction.</li>
                            <li class="mb-2">{{ auth()->id() === $transaction->seller_id ? 'Once payment is received, mark the transaction as complete.' : 'Once you receive the product, the seller will mark the transaction as complete.' }}</li>
                            <li>If there are any issues, you can cancel the transaction or contact support.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
