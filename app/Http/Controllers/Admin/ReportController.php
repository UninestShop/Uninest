<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReport;
use App\Http\Requests\ResolveReportRequest;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        try {
            $reports = ProductReport::with(['product', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
                
            return view('admin.reports.index', compact('reports'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading reports: ' . $e->getMessage());
        }
    }
    
    public function show(ProductReport $report)
    {
        try {
            $report->load(['product', 'user']);
            
            // Mark as reviewed if it's still pending
            if ($report->status === 'pending') {
                $report->update(['status' => 'reviewed']);
            }
            
            return view('admin.reports.show', compact('report'));
        } catch (\Exception $e) {
            return redirect()->route('admin.reports.index')->with('error', 'Error viewing report: ' . $e->getMessage());
        }
    }
    
    public function resolve(ResolveReportRequest $request, ProductReport $report)
    {
        try {
            $report->update([
                'status' => 'resolved',
                'resolution_notes' => $request->resolution_notes
            ]);
            return redirect()->route('admin.reports.index')
                ->with('success', 'Report has been resolved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error resolving report: ' . $e->getMessage());
        }
    }
}
