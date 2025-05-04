<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Http\Requests\StoreDonationRequest;
use App\Http\Requests\UpdateDonationRequest;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    /**
     * Display a listing of donations.
     */
    public function index()
    {
        $donations = Donation::with('campaign')
            ->latest()
            ->paginate(10);

        return view('donations.index', compact('donations'));
    }

    /**
     * Show the form for creating a new donation.
     */
    public function create()
    {
        $campaigns = Campaign::where('status', 'active')->get();
        return view('donations.create', compact('campaigns'));
    }

    /**
     * Store a newly created donation in storage.
     */
    public function store(StoreDonationRequest $request)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the donation
            $donation = Donation::create($request->validated());

            // Update campaign's funds raised
            $campaign = Campaign::find($request->campaign_id);
            $campaign->increment('funds_raised', $request->amount);

            // Check if campaign goal is reached
            if ($campaign->funds_raised >= $campaign->goal_amount) {
                $campaign->update(['status' => 'completed']);
            }

            DB::commit();

            return redirect()->route('donations.index')
                ->with('success', 'Donation recorded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record donation: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified donation.
     */
    public function show(Donation $donation)
    {
        return view('donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified donation.
     */
    public function edit(Donation $donation)
    {
        $campaigns = Campaign::where('status', 'active')->get();
        return view('donations.edit', compact('donation', 'campaigns'));
    }

    /**
     * Update the specified donation in storage.
     */
    public function update(UpdateDonationRequest $request, Donation $donation)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Calculate the difference in amount
            $amountDifference = $request->amount - $donation->amount;

            // Update the donation
            $donation->update($request->validated());

            // Update campaign's funds raised if amount changed
            if ($amountDifference != 0) {
                $campaign = Campaign::find($request->campaign_id);
                $campaign->increment('funds_raised', $amountDifference);

                // Check if campaign goal is reached
                if ($campaign->funds_raised >= $campaign->goal_amount) {
                    $campaign->update(['status' => 'completed']);
                }
            }

            DB::commit();

            return redirect()->route('donations.index')
                ->with('success', 'Donation updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update donation: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified donation from storage.
     */
    public function destroy(Donation $donation)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Decrease campaign's funds raised
            $campaign = $donation->campaign;
            $campaign->decrement('funds_raised', $donation->amount);

            // Delete the donation
            $donation->delete();

            DB::commit();

            return redirect()->route('donations.index')
                ->with('success', 'Donation deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete donation: ' . $e->getMessage());
        }
    }
} 