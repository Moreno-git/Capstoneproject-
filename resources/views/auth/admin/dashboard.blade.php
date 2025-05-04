@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Admin Dashboard</h2>
    </div>

    <div class="row">
        <!-- Donations Summary Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Donations</h5>
                    <h2 class="mb-0">₱{{ number_format($totalDonations ?? 0, 2) }}</h2>
                    <p class="text-muted small mb-0">From {{ $donationCount ?? 0 }} donations</p>
                </div>
            </div>
        </div>

        <!-- Active Campaigns Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success">Active Campaigns</h5>
                    <h2 class="mb-0">{{ $activeCampaigns ?? 0 }}</h2>
                    <p class="text-muted small mb-0">Currently running</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-info">Recent Activities</h5>
                    <h2 class="mb-0">{{ $recentActivities ?? 0 }}</h2>
                    <p class="text-muted small mb-0">In the last 24 hours</p>
                </div>
            </div>
        </div>

        <!-- Total Donors Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-warning">Total Donors</h5>
                    <h2 class="mb-0">{{ $totalDonors ?? 0 }}</h2>
                    <p class="text-muted small mb-0">Unique contributors</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Donations Table -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Recent Donations</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Donor</th>
                            <th>Campaign</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDonations ?? [] as $donation)
                        <tr>
                            <td>{{ $donation->donor_name }}</td>
                            <td>{{ $donation->campaign_name }}</td>
                            <td>₱{{ number_format($donation->amount, 2) }}</td>
                            <td>{{ $donation->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $donation->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No recent donations</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush 