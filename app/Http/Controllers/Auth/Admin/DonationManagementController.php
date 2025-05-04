<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DonationManagementController extends Controller
{
    public function index()
    {
        // Get summary statistics with month-over-month changes
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Monetary donations
        $currentMonetaryTotal = Donation::where('type', 'monetary')
            ->whereMonth('created_at', $currentMonth->month)
            ->sum('amount');
        $lastMonetaryTotal = Donation::where('type', 'monetary')
            ->whereMonth('created_at', $lastMonth->month)
            ->sum('amount');
        $monetaryChange = $lastMonetaryTotal > 0 
            ? (($currentMonetaryTotal - $lastMonetaryTotal) / $lastMonetaryTotal) * 100 
            : 100;

        // Non-monetary donations
        $currentNonMonetaryCount = Donation::where('type', 'non_monetary')
            ->whereMonth('created_at', $currentMonth->month)
            ->count();
        $lastNonMonetaryCount = Donation::where('type', 'non_monetary')
            ->whereMonth('created_at', $lastMonth->month)
            ->count();
        $nonMonetaryChange = $lastNonMonetaryCount > 0 
            ? (($currentNonMonetaryCount - $lastNonMonetaryCount) / $lastNonMonetaryCount) * 100 
            : 100;

        // Campaign donations
        $currentCampaignTotal = Donation::whereNotNull('campaign_id')
            ->whereMonth('created_at', $currentMonth->month)
            ->sum('amount');
        $lastCampaignTotal = Donation::whereNotNull('campaign_id')
            ->whereMonth('created_at', $lastMonth->month)
            ->sum('amount');
        $campaignChange = $lastCampaignTotal > 0 
            ? (($currentCampaignTotal - $lastCampaignTotal) / $lastCampaignTotal) * 100 
            : 100;

        // Donor count
        $currentDonorCount = Donation::whereMonth('created_at', $currentMonth->month)
            ->distinct('donor_email')
            ->count();
        $lastDonorCount = Donation::whereMonth('created_at', $lastMonth->month)
            ->distinct('donor_email')
            ->count();
        $donorChange = $lastDonorCount > 0 
            ? (($currentDonorCount - $lastDonorCount) / $lastDonorCount) * 100 
            : 100;

        // Recent donations
        $recentDonations = Donation::with(['donor', 'campaign'])
            ->latest()
            ->take(5)
            ->get();

        // Pending drop-offs
        $pendingDropOffs = Donation::where('type', 'non_monetary')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('auth.admin.donations.index', compact(
            'currentMonetaryTotal',
            'monetaryChange',
            'currentNonMonetaryCount',
            'nonMonetaryChange',
            'currentCampaignTotal',
            'campaignChange',
            'currentDonorCount',
            'donorChange',
            'recentDonations',
            'pendingDropOffs'
        ));
    }

    public function all()
    {
        $donations = Donation::with(['donor', 'campaign'])
            ->latest()
            ->paginate(15);

        return view('auth.admin.donations.all', compact('donations'));
    }

    public function show(Donation $donation)
    {
        return view('auth.admin.donations.show', compact('donation'));
    }

    public function dropoffs()
    {
        $pendingDropOffs = Donation::where('type', 'non_monetary')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        $completedDropOffs = Donation::where('type', 'non_monetary')
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);

        return view('auth.admin.donations.dropoffs', compact('pendingDropOffs', 'completedDropOffs'));
    }

    public function updateDropoffStatus(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'status' => 'required|in:completed,rejected'
        ]);

        $donation->update([
            'status' => $validated['status']
        ]);

        return back()->with('success', 'Drop-off status updated successfully');
    }
} 