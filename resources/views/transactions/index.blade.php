@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>My Transactions</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Role</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($transaction->product->photos)
                                            <img src="{{ $transaction->product->first_photo }}" 
                                                 class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        {{ $transaction->product->name }}
                                    </div>
                                </td>
                                <td>
                                    {{ auth()->id() === $transaction->buyer_id ? 'Buyer' : 'Seller' }}
                                </td>
                                <td>${{ number_format($transaction->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $transaction->status === 'completed' ? 'success' : 
                                        ($transaction->status === 'pending' ? 'warning' : 'danger') 
                                    }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($transaction->status === 'pending')
                                        @if(auth()->id() === $transaction->seller_id)
                                            <form action="{{ route('transactions.complete', $transaction) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    Complete
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('transactions.cancel', $transaction) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    No transactions found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
