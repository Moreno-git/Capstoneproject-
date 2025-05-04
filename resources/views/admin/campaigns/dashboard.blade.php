@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Campaign Dashboard</h1>
            <p class="text-muted">Overview of your campaign performance</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Campaign
            </a>
            <a href="{{ route('admin.campaigns.manage') }}" class="btn btn-outline-primary">
                <i class="fas fa-list me-2"></i>Manage Campaigns
            </a>
        </div>
    </div>

    <!-- Campaign Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="fas fa-bullhorn text-primary fa-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Active Campaigns</h6>
                            <h4 class="mb-0">{{ $campaigns->where('status', 'Ongoing')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="fas fa-hand-holding-heart text-success fa-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Donations</h6>
                            <h4 class="mb-0">{{ $recentDonations->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                <i class="fas fa-chart-line text-info fa-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Raised</h6>
                            <h4 class="mb-0">₱{{ number_format($recentDonations->sum('amount'), 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="fas fa-users text-warning fa-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Donors</h6>
                            <h4 class="mb-0">{{ $recentDonations->unique('donor_email')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Campaigns -->
    <h5 class="mb-4">Active Campaigns</h5>
    <div class="row g-4 mb-4">
        @foreach($campaigns->where('status', 'Ongoing') as $campaign)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">{{ $campaign->title }}</h5>
                        <span class="badge bg-{{ $campaign->status === 'Ongoing' ? 'success' : ($campaign->status === 'Paused' ? 'warning' : 'secondary') }}">
                            {{ $campaign->status }}
                        </span>
                    </div>
                    <p class="text-muted small mb-3">{{ Str::limit($campaign->description, 100) }}</p>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Progress</span>
                            <span class="text-muted small">{{ round(($campaign->current_amount / $campaign->goal_amount) * 100) }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ ($campaign->current_amount / $campaign->goal_amount) * 100 }}%">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="text-muted small">₱{{ number_format($campaign->current_amount, 2) }}</span>
                            <span class="text-muted small">₱{{ number_format($campaign->goal_amount, 2) }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Recent Donations -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Recent Donations</h5>
            <a href="{{ route('admin.donations.index') }}" class="btn btn-sm btn-outline-primary">
                View All
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Donor</th>
                            <th>Campaign</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDonations as $donation)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-secondary-subtle me-2 d-flex align-items-center justify-content-center" 
                                         style="width: 32px; height: 32px;">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $donation->donor_name }}</div>
                                        <div class="small text-muted">{{ $donation->donor_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $donation->campaign->title }}</td>
                            <td>₱{{ number_format($donation->amount, 2) }}</td>
                            <td>
                                <div>{{ $donation->created_at->format('M d, Y') }}</div>
                                <div class="small text-muted">{{ $donation->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="pe-4">
                                <span class="badge bg-{{ $donation->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No recent donations found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 