<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DonationsExport;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request)
    {
        $query = Donation::with(['campaign', 'donor']);
        $campaignQuery = Campaign::withCount('donations');

        // Apply date filter
        if ($request->filled('date_range')) {
            $days = $request->input('date_range');
            $startDate = Carbon::now()->subDays($days);
            $query->where('created_at', '>=', $startDate);
            $campaignQuery->where('created_at', '>=', $startDate);
        }

        // Apply campaign filter
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->input('campaign_id'));
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $donations = $query->latest()->get();
        $campaigns = $campaignQuery->latest()->get();

        return view('admin.reports.index', compact('donations', 'campaigns'));
    }

    /**
     * Export reports data.
     */
    public function export(Request $request)
    {
        $fileName = 'donations-report-' . Carbon::now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new DonationsExport($request), $fileName);
    }
} 