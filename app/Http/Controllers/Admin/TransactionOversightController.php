<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\IssueReport;
use App\Http\Requests\ResolveIssueRequest;

class TransactionOversightController extends Controller
{
    public function index()
    {
        try {
            $transactions = Transaction::with(['user', 'product'])
                ->latest()
                ->paginate(20);
            return view('admin.transactions.index', compact('transactions'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load transactions.');
        }
    }

    public function showIssues()
    {
        try {
            $issues = IssueReport::with(['transaction', 'user'])
                ->where('status', 'pending')
                ->latest()
                ->paginate(15);
            return view('admin.transactions.issues', compact('issues'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load issues.');
        }
    }

    public function resolveIssue(ResolveIssueRequest $request, IssueReport $issue)
    {
        try {
            $validated = $request->validated();

            $issue->update([
                'status' => $validated['status'],
                'resolution' => $validated['resolution'],
                'resolved_at' => now(),
                'resolved_by' => auth()->id()
            ]);

            return redirect()->back()
                ->with('success', 'Issue has been resolved successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to resolve issue.');
        }
    }

    public function transactionLogs()
    {
        try {
            $logs = Transaction::with(['user', 'product'])
                ->where('created_at', '>=', now()->subDays(30))
                ->orderBy('created_at', 'desc')
                ->paginate(50);
            return view('admin.transactions.logs', compact('logs'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load transaction logs.');
        }
    }
}
