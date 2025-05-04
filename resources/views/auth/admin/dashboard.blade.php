@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Admin Dashboard</h2>
    </div>

    <div class="row">
        <!-- Donations Summary Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="donation-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-primary bg-opacity-10">
                                <i class="fas fa-money-bill text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title text-primary mb-1">Total Donations</h5>
                            <h2 class="mb-0">₱{{ number_format($totalDonations ?? 0, 2) }}</h2>
                            <p class="text-muted small mb-0">From {{ $donationCount ?? 0 }} donations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Campaigns Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="donation-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-success bg-opacity-10">
                                <i class="fas fa-bullhorn text-success"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title text-success mb-1">Active Campaigns</h5>
                            <h2 class="mb-0">{{ $activeCampaigns ?? 0 }}</h2>
                            <p class="text-muted small mb-0">Currently running</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="donation-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-info bg-opacity-10">
                                <i class="fas fa-chart-line text-info"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title text-info mb-1">Recent Activities</h5>
                            <h2 class="mb-0">{{ $recentActivities ?? 0 }}</h2>
                            <p class="text-muted small mb-0">In the last 24 hours</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Donors Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="donation-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-warning bg-opacity-10">
                                <i class="fas fa-users text-warning"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title text-warning mb-1">Total Donors</h5>
                            <h2 class="mb-0">{{ $totalDonors ?? 0 }}</h2>
                            <p class="text-muted small mb-0">Unique contributors</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Donations Table -->
    <div class="donation-card">
        <div class="card-header">
            <h5 class="mb-0">Recent Donations</h5>
        </div>
        <div class="donation-table-container">
            <div class="table-responsive">
                <table class="donation-table">
                    <thead>
                        <tr>
                            <th>Donor</th>
                            <th>Campaign</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDonations ?? [] as $donation)
                        <tr>
                            <td>{{ $donation->donor_name }}</td>
                            <td>{{ $donation->campaign ? $donation->campaign->title : '-' }}</td>
                            <td>
                                @if($donation->type === 'monetary')
                                    <span class="fw-semibold">₱{{ number_format($donation->amount, 2) }}</span>
                                @else
                                    <span class="fw-semibold">{{ $donation->item_name }}</span>
                                    <small class="text-muted d-block">{{ $donation->quantity }} units</small>
                                @endif
                            </td>
                            <td>
                                <span>{{ $donation->created_at->format('M d, Y') }}</span>
                                <small class="text-muted d-block">{{ $donation->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $donation->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.donations.show', $donation) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No recent donations</td>
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
<link rel="stylesheet" href="{{ asset('css/donation-panel.css') }}">
@endpush 