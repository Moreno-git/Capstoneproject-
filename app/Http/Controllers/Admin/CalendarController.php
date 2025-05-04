<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display the calendar view with campaign events.
     */
    public function index()
    {
        $campaigns = Campaign::select('id', 'title', 'start_date', 'end_date', 'status')
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'start' => $campaign->start_date->format('Y-m-d'),
                    'end' => $campaign->end_date->format('Y-m-d'),
                    'backgroundColor' => $campaign->status === 'active' ? '#28a745' : '#ffc107',
                    'borderColor' => $campaign->status === 'active' ? '#28a745' : '#ffc107',
                    'extendedProps' => [
                        'status' => ucfirst($campaign->status)
                    ]
                ];
            });

        return view('admin.calendar.index', compact('campaigns'));
    }

    /**
     * Store a new campaign event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $campaign = Campaign::create([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'active',
        ]);

        return response()->json([
            'id' => $campaign->id,
            'title' => $campaign->title,
            'start' => $campaign->start_date->format('Y-m-d'),
            'end' => $campaign->end_date->format('Y-m-d'),
            'className' => 'bg-success',
        ]);
    }

    /**
     * Update a campaign event's dates.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $campaign->update([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return response()->json([
            'message' => 'Campaign dates updated successfully',
        ]);
    }

    /**
     * Delete a campaign event.
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return response()->json([
            'message' => 'Campaign deleted successfully',
        ]);
    }
} 