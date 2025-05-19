<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UrgentCampaign;

class UrgentCampaignController extends Controller
{
    public function index()
    {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('urgent-funds', UrgentCampaignController::class);
        });
    }

    public function create()
    {
        return view('admin.urgent-campaigns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'goal' => 'required|numeric',
            'description' => 'nullable|string',
            'goal' => 'required|numeric|min:0',
            'raised' =>  'required|numeric|min:0',
        ]);

        UrgentCampaign::create([
            'title' => $request->title,
            'goal' => $request->goal,
            'description' => $request->description,
            'goal' => $request->goal,
            'raised' => $request->raised,
            'is_urgent' => true, // Automatically mark as urgent
        ]);

        return redirect()->route('urgent-campaigns.index')->with('success', 'Campaign created successfully.');
    }

    public function edit($id)
    {
        $campaign = UrgentCampaign::findOrFail($id);
        return view('admin.urgent-campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'goal' => 'required|numeric',
            'description' => 'nullable|string',
            'goal' => 'required|numeric|min:0',
            'raised' =>  'required|numeric|min:0', 
            'is_urgent' => 'boolean',
        ]);

        $campaign = UrgentCampaign::findOrFail($id);
        $campaign->update($request->all());

        return redirect()->route('urgent-campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    public function destroy($id)
    {
        $campaign = UrgentCampaign::findOrFail($id);
        $campaign->delete();

        return redirect()->route('urgent-campaigns.index')->with('success', 'Campaign deleted successfully.');
    }
}