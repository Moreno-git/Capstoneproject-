<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get summary statistics
        $totalMonetaryDonations = Donation::where('amount', '>', 0)->sum('amount');
        $totalNonMonetaryDonations = Donation::where('amount', 0)->count();
        $totalDonors = Donation::distinct('donor_email')->count();
        $newDonors = Donation::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->distinct('donor_email')
            ->count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $completedCampaigns = Campaign::where('status', 'completed')->count();
        $familiesHelped = Campaign::sum('funds_raised');

        // Get donations over time (last 6 months)
        $donationsOverTime = Donation::where('amount', '>', 0)
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as amount')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get top performing campaigns
        $topCampaigns = Campaign::withCount('donations')
            ->withSum('donations', 'amount')
            ->orderBy('donations_sum_amount', 'desc')
            ->take(5)
            ->get();

        return view('reports.index', compact(
            'totalMonetaryDonations',
            'totalNonMonetaryDonations',
            'totalDonors',
            'newDonors',
            'activeCampaigns',
            'completedCampaigns',
            'familiesHelped',
            'donationsOverTime',
            'topCampaigns'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'excel');
        
        // Get the data for export
        $data = [
            'summary' => [
                'Total Monetary Donations' => Donation::where('amount', '>', 0)->sum('amount'),
                'Total Non-Monetary Donations' => Donation::where('amount', 0)->count(),
                'Total Donors' => Donation::distinct('donor_email')->count(),
                'New Donors This Month' => Donation::where('created_at', '>=', Carbon::now()->startOfMonth())
                    ->distinct('donor_email')
                    ->count(),
                'Active Campaigns' => Campaign::where('status', 'active')->count(),
                'Completed Campaigns' => Campaign::where('status', 'completed')->count(),
                'Families Helped' => Campaign::sum('funds_raised'),
            ],
            'campaigns' => Campaign::withCount('donations')
                ->withSum('donations', 'amount')
                ->orderBy('donations_sum_amount', 'desc')
                ->get(),
            'donations' => Donation::with('campaign')
                ->orderBy('created_at', 'desc')
                ->get(),
        ];

        if ($format === 'excel') {
            // TODO: Implement Excel export using a package like Maatwebsite/Laravel-Excel
            return response()->download('reports.xlsx');
        } else {
            // TODO: Implement PDF export using a package like barryvdh/laravel-dompdf
            return response()->download('reports.pdf');
        }
    }
}
