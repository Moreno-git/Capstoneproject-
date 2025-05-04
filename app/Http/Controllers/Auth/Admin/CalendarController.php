<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with('category')->get();
        $categories = Category::all();
        
        // Format campaigns for calendar display
        $events = $campaigns->map(function ($campaign) {
            return [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'start' => $campaign->start_date,
                'end' => $campaign->end_date,
                'backgroundColor' => $campaign->category ? $campaign->category->color : '#6c757d', // Default gray color if no category
                'description' => $campaign->description,
                'category_id' => $campaign->category_id,
                'pledged_amount' => $campaign->pledged_amount,
                'pledged_quantity' => $campaign->pledged_quantity
            ];
        });

        return view('auth.admin.calendar.index', compact('events', 'categories'));
    }

    public function store(Request $request)
    {
        \Log::info('Request data:', $request->all());
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category_id' => 'required|exists:categories,id', // This ensures category exists
            'pledged_amount' => 'nullable|numeric',
            'pledged_quantity' => 'nullable|numeric'
        ]);

        $campaign = Campaign::create($validated);
        return response()->json($campaign->load('category'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'category_id' => 'sometimes|exists:categories,id', // This ensures category exists
            'pledged_amount' => 'nullable|numeric',
            'pledged_quantity' => 'nullable|numeric'
        ]);

        $campaign->update($validated);
        return response()->json($campaign->load('category'));
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return response()->json(['message' => 'Campaign deleted successfully']);
    }
} 