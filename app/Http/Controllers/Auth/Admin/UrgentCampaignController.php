<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UrgentCampaignController extends Controller
{
    public function index()
    {
        // Get featured urgent campaign
        $featuredCampaign = Campaign::where('is_urgent', true)
            ->where('end_date', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        // Get recent donations for urgent campaigns
        $recentDonations = Donation::whereHas('campaign', function($query) {
            $query->where('is_urgent', true);
        })
        ->with(['donor', 'campaign'])
        ->latest()
        ->take(5)
        ->get();

        // Calculate campaign statistics
        if ($featuredCampaign) {
            $daysRemaining = now()->diffInDays($featuredCampaign->end_date, false);
            $percentageComplete = ($featuredCampaign->total_donations / $featuredCampaign->goal_amount) * 100;
        } else {
            $daysRemaining = 0;
            $percentageComplete = 0;
        }

        return view('admin.campaigns.urgent', compact(
            'featuredCampaign',
            'recentDonations',
            'daysRemaining',
            'percentageComplete'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:0',
            'end_date' => 'required|date|after:today',
            'is_urgent' => 'boolean'
        ]);

        // Set default values
        $validated['is_urgent'] = true; // Always set urgent to true for this controller
        $validated['total_donations'] = 0;

        $campaign = Campaign::create($validated);

        return redirect()
            ->route('admin.urgent-campaigns.index')
            ->with('success', 'Urgent campaign created successfully');
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'goal_amount' => 'sometimes|required|numeric|min:0',
            'end_date' => 'sometimes|required|date|after:today',
            'is_urgent' => 'sometimes|boolean'
        ]);

        $campaign->update($validated);

        return redirect()
            ->route('admin.urgent-campaigns.index')
            ->with('success', 'Campaign updated successfully');
    }

    public function archive(Campaign $campaign)
    {
        $campaign->update(['is_archived' => true]);

        return redirect()
            ->route('admin.urgent-campaigns.index')
            ->with('success', 'Campaign archived successfully');
    }
} 