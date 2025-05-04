<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DonationManagementController extends Controller
{
    /**
     * Display a listing of the donations.
     */
    public function index(Request $request)
    {
        // Get current month statistics
        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Current month totals
        $currentMonthStats = $this->getMonthlyStats($now);
        $lastMonthStats = $this->getMonthlyStats($lastMonth);

        // Calculate percentage changes
        $monetaryChange = $this->calculatePercentageChange(
            $lastMonthStats['monetaryTotal'], 
            $currentMonthStats['monetaryTotal']
        );
        
        $nonMonetaryChange = $this->calculatePercentageChange(
            $lastMonthStats['nonMonetaryCount'], 
            $currentMonthStats['nonMonetaryCount']
        );
        
        $campaignChange = $this->calculatePercentageChange(
            $lastMonthStats['campaignTotal'], 
            $currentMonthStats['campaignTotal']
        );
        
        $donorChange = $this->calculatePercentageChange(
            $lastMonthStats['donorCount'], 
            $currentMonthStats['donorCount']
        );

        // Get recent donations with search
        $donations = Donation::with(['campaign'])
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('donor_name', 'like', "%{$search}%")
                      ->orWhere('donor_email', 'like', "%{$search}%")
                      ->orWhere('item_description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(5);

        // Get pending dropoffs
        $pendingDropoffs = Donation::where('type', 'non-monetary')
            ->where('status', 'pending')
            ->where('payment_method', 'dropoff')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.donations.index', [
            'donations' => $donations,
            'pendingDropoffs' => $pendingDropoffs,
            'monetaryTotal' => $currentMonthStats['monetaryTotal'],
            'nonMonetaryCount' => $currentMonthStats['nonMonetaryCount'],
            'campaignTotal' => $currentMonthStats['campaignTotal'],
            'donorCount' => $currentMonthStats['donorCount'],
            'monetaryChange' => $monetaryChange,
            'nonMonetaryChange' => $nonMonetaryChange,
            'campaignChange' => $campaignChange,
            'donorChange' => $donorChange
        ]);
    }

    /**
     * Get monthly statistics
     */
    private function getMonthlyStats($date)
    {
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $monetaryTotal = Donation::where('type', 'monetary')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $nonMonetaryCount = Donation::where('type', 'non-monetary')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $campaignTotal = Campaign::join('donations', 'campaigns.id', '=', 'donations.campaign_id')
            ->whereBetween('donations.created_at', [$startOfMonth, $endOfMonth])
            ->where('donations.type', 'monetary')
            ->sum('donations.amount');

        $donorCount = Donation::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->distinct('donor_email')
            ->count('donor_email');

        return [
            'monetaryTotal' => $monetaryTotal,
            'nonMonetaryCount' => $nonMonetaryCount,
            'campaignTotal' => $campaignTotal,
            'donorCount' => $donorCount
        ];
    }

    /**
     * Calculate percentage change between two values
     */
    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        
        return round((($newValue - $oldValue) / $oldValue) * 100, 2);
    }

    /**
     * Display all donations.
     */
    public function all(Request $request)
    {
        // Get current month statistics
        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Current month totals
        $currentMonthStats = $this->getMonthlyStats($now);
        $lastMonthStats = $this->getMonthlyStats($lastMonth);

        // Calculate percentage changes
        $monetaryChange = $this->calculatePercentageChange(
            $lastMonthStats['monetaryTotal'], 
            $currentMonthStats['monetaryTotal']
        );
        
        $nonMonetaryChange = $this->calculatePercentageChange(
            $lastMonthStats['nonMonetaryCount'], 
            $currentMonthStats['nonMonetaryCount']
        );
        
        $campaignChange = $this->calculatePercentageChange(
            $lastMonthStats['campaignTotal'], 
            $currentMonthStats['campaignTotal']
        );
        
        $donorChange = $this->calculatePercentageChange(
            $lastMonthStats['donorCount'], 
            $currentMonthStats['donorCount']
        );

        $donations = Donation::with(['campaign'])
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('donor_name', 'like', "%{$search}%")
                      ->orWhere('donor_email', 'like', "%{$search}%")
                      ->orWhere('item_description', 'like', "%{$search}%");
                });
            })
            ->when($request->type, function($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->campaign_id, function($query, $campaignId) {
                $query->where('campaign_id', $campaignId);
            })
            ->latest()
            ->paginate(15);

        $campaigns = Campaign::pluck('title', 'id');

        return view('admin.donations.all', [
            'donations' => $donations,
            'campaigns' => $campaigns,
            'monetaryTotal' => $currentMonthStats['monetaryTotal'],
            'nonMonetaryCount' => $currentMonthStats['nonMonetaryCount'],
            'campaignTotal' => $currentMonthStats['campaignTotal'],
            'donorCount' => $currentMonthStats['donorCount'],
            'monetaryChange' => $monetaryChange,
            'nonMonetaryChange' => $nonMonetaryChange,
            'campaignChange' => $campaignChange,
            'donorChange' => $donorChange
        ]);
    }

    /**
     * Display drop-off donations.
     */
    public function dropoffs()
    {
        $pendingDropoffs = Donation::with(['campaign'])
            ->where('type', 'non-monetary')
            ->where('payment_method', 'dropoff')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        $completedDropoffs = Donation::with(['campaign'])
            ->where('type', 'non-monetary')
            ->where('payment_method', 'dropoff')
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);

        return view('admin.donations.dropoffs', compact('pendingDropoffs', 'completedDropoffs'));
    }

    /**
     * Update the status of a drop-off donation.
     */
    public function updateDropoffStatus(Request $request, Donation $donation)
    {
        if ($donation->type !== 'non-monetary' || $donation->payment_method !== 'dropoff') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid donation type'
            ], 400);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $donation->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Donation status updated successfully'
        ]);
    }

    /**
     * Display the specified donation.
     */
    public function show(Donation $donation)
    {
        $donation->load(['campaign']);
        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Update the status of a donation.
     */
    public function updateStatus(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,rejected'
        ]);

        $donation->update($validated);

        return back()->with('success', 'Donation status updated successfully');
    }
} 