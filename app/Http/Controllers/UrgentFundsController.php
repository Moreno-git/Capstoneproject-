<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Campaign;

class UrgentFundsController extends Controller
{
    public function index()
    {
        $urgentCampaigns = Campaign::where('is_urgent', true)->get();
        $allCampaigns = Campaign::all();
        return view('admin.urgent-funds.index', compact('urgentCampaigns', 'allCampaigns'));
    }

    public function create()
    {
        return view('admin.urgent-funds.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:0',
            'funds_raised' => 'nullable|numeric|min:0',
        ]);

        $validated['is_urgent'] = true;
        $validated['funds_raised'] = $validated['funds_raised'] ?? 0;
        $validated['status'] = 'active';
        $validated['start_date'] = now();
        $validated['end_date'] = now()->addDays(30);

        Campaign::create($validated);

        return redirect()->route('admin.urgent-funds.index')
            ->with('success', 'Urgent campaign created successfully.');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.urgent-funds.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:0',
            'funds_raised' => 'nullable|numeric|min:0',
        ]);

        $validated['is_urgent'] = true;
        $campaign->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Campaign updated successfully',
                'campaign' => $campaign
            ]);
        }

        return redirect()->route('admin.urgent-funds.index')
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('admin.urgent-funds.index')
            ->with('success', 'Campaign deleted successfully.');
    }

    public function profile()
    {
        $admin = Auth::user(); // Get the currently authenticated admin
        return view('auth.admin.profile', compact('admin'));
    }
}
