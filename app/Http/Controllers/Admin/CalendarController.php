<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display the calendar view with campaign events.
     */
    public function index()
    {
        $campaigns = Campaign::with('category')->get()->map(function ($campaign) {
            // Determine category type and color
            $categoryType = $campaign->category ? strtolower($campaign->category->name) : 'other';
            
            // Format pledged information based on campaign type
            $pledgedInfo = '';
            if ($campaign->pledged_amount) {
                $pledgedInfo = '₱' . number_format($campaign->pledged_amount, 2);
            } elseif ($campaign->pledged_quantity) {
                $pledgedInfo = $campaign->pledged_quantity . ' kg';
            }

            return [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'start' => $campaign->start_date->format('Y-m-d'),
                'end' => $campaign->end_date->format('Y-m-d'),
                'category' => $categoryType,
                'extendedProps' => [
                    'status' => ucfirst($campaign->status),
                    'category' => $categoryType,
                    'pledged' => $pledgedInfo,
                    'description' => $campaign->description
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
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category_id' => 'required|exists:categories,id',
            'pledged_amount' => 'nullable|numeric',
            'pledged_quantity' => 'nullable|numeric'
        ]);

        $campaign = Campaign::create($validated);
        
        return response()->json([
            'id' => $campaign->id,
            'title' => $campaign->title,
            'start' => $campaign->start_date->format('Y-m-d'),
            'end' => $campaign->end_date->format('Y-m-d'),
            'category' => $campaign->category ? strtolower($campaign->category->name) : 'other',
            'extendedProps' => [
                'status' => 'Active',
                'category' => $campaign->category ? strtolower($campaign->category->name) : 'other',
                'pledged' => $campaign->pledged_amount ? '₱' . number_format($campaign->pledged_amount, 2) : 
                            ($campaign->pledged_quantity ? $campaign->pledged_quantity . ' kg' : null),
                'description' => $campaign->description
            ]
        ]);
    }

    /**
     * Update a campaign event's dates.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $campaign->update($validated);

        return response()->json([
            'message' => 'Campaign dates updated successfully',
            'campaign' => [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'start' => $campaign->start_date->format('Y-m-d'),
                'end' => $campaign->end_date->format('Y-m-d'),
                'category' => $campaign->category ? strtolower($campaign->category->name) : 'other',
                'extendedProps' => [
                    'status' => ucfirst($campaign->status),
                    'category' => $campaign->category ? strtolower($campaign->category->name) : 'other',
                    'pledged' => $campaign->pledged_amount ? '₱' . number_format($campaign->pledged_amount, 2) : 
                                ($campaign->pledged_quantity ? $campaign->pledged_quantity . ' kg' : null),
                    'description' => $campaign->description
                ]
            ]
        ]);
    }

    /**
     * Delete a campaign event.
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return response()->json(['message' => 'Campaign deleted successfully']);
    }
} 