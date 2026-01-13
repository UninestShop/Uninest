<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use DataTables;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Transaction::with(['user', 'product']);
            
            return DataTables::of($transactions)
                ->addIndexColumn()
                ->editColumn('amount', function($transaction) {
                    return number_format($transaction->amount, 2);
                })
                ->addColumn('actions', function($transaction) {
                    return '';  // Will be rendered by JS
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        
        return view('admin.transactions.index');
    }

    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }

    public function issues()
    {
        $transactions = Transaction::with(['user', 'product'])
            ->where('issue_reported', true)
            ->latest()
            ->paginate(10);
        return view('admin.transactions.issues', compact('transactions'));
    }

    public function resolve(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'resolution_notes' => 'required|string',
            'status' => 'required|in:resolved,refunded,cancelled'
        ]);

        $transaction->update([
            'resolution_notes' => $validated['resolution_notes'],
            'status' => $validated['status']
        ]);

        return redirect()->route('admin.transactions.issues')
            ->with('success', 'Issue resolved successfully');
    }
}
