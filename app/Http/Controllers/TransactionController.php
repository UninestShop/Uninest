<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Http\Requests\TransactionRequest;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        try {
            $this->middleware('auth');
        } catch (\Exception $e) {
            report($e);
            abort(500, 'Authentication middleware registration failed');
        }
    }

    public function index()
    {
        try {
            $transactions = Transaction::where('buyer_id', auth()->id())
                ->orWhere('seller_id', auth()->id())
                ->with(['product', 'buyer', 'seller'])
                ->latest()
                ->paginate(10);

            return view('transactions.index', compact('transactions'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to retrieve transactions: ' . $e->getMessage());
        }
    }

    public function initiate(TransactionRequest $request, Product $product)
    {
        try {
            // Validate the buyer can't be the seller
            if ($product->user_id === auth()->id()) {
                return back()->with('error', 'You cannot buy your own product');
            }

            // Check if product is available
            if ($product->is_sold || $product->status !== 'approved') {
                return back()->with('error', 'This product is not available');
            }

            $transaction = Transaction::create([
                'product_id' => $product->id,
                'seller_id' => $product->user_id,
                'buyer_id' => auth()->id(),
                'amount' => $product->selling_price,
                'status' => 'pending',
                'quantity' => $request->input('quantity', 1),
                'payment_method' => $request->input('payment_method', 'cash'),
                'notes' => $request->input('notes'),
            ]);

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaction initiated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to initiate transaction: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified transaction details.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        try {
            // Check if the authenticated user is authorized to view this transaction
            if (auth()->id() !== $transaction->buyer_id && 
                auth()->id() !== $transaction->seller_id) {
                abort(403, 'Unauthorized action.');
            }
            
            // Load necessary relationships
            $transaction->load(['product', 'buyer', 'seller']);
            
            return view('transactions.show', compact('transaction'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to retrieve transaction details: ' . $e->getMessage());
        }
    }

    public function complete(Transaction $transaction)
    {
        try {
            // Verify the user is the seller
            if ($transaction->seller_id !== auth()->id()) {
                return back()->with('error', 'Unauthorized action');
            }

            $transaction->update(['status' => 'completed']);
            $transaction->product->update(['is_sold' => true]);

            return back()->with('success', 'Transaction completed successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to complete transaction: ' . $e->getMessage());
        }
    }

    public function cancel(Transaction $transaction)
    {
        try {
            // Verify the user is involved in the transaction
            if (!in_array(auth()->id(), [$transaction->buyer_id, $transaction->seller_id])) {
                return back()->with('error', 'Unauthorized action');
            }

            $transaction->update([
                'status' => 'cancelled',
                'cancelled_by' => auth()->id()
            ]);

            return back()->with('success', 'Transaction cancelled successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel transaction: ' . $e->getMessage());
        }
    }
}
