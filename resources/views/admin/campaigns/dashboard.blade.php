@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Campaign Management</h1>
            <p class="text-muted">Monitor and manage your campaigns</p>
        </div>
        <div>
            <a href="{{ route('admin.campaigns.list') }}" class="btn btn-primary">
                Manage Campaign
            </a>
        </div>
    </div>

    <!-- Campaign Cards -->
    <div class="row g-4 mb-4">
        @foreach($campaigns as $campaign)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">{{ $campaign->title }}</h5>
                        <div class="rounded-pill px-3 py-1 {{ $campaign->status === 'Ongoing' ? 'bg-success-subtle text-success' : ($campaign->status === 'Paused' ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary') }}">
                            {{ $campaign->status }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Progress</label>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-body-secondary">${{ number_format($campaign->current_amount, 0) }}</span>
                            <span class="text-body-secondary">/ ${{ number_format($campaign->goal_amount, 0) }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar {{ $campaign->status === 'Ongoing' ? 'bg-success' : ($campaign->status === 'Paused' ? 'bg-warning' : 'bg-secondary') }}" 
                                 role="progressbar" 
                                 style="width: {{ ($campaign->current_amount / $campaign->goal_amount) * 100 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Recent Donations -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">Recent Donations</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
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
                        @foreach($recentDonations as $donation)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($donation->donor_avatar)
                                        <img src="{{ Storage::url($donation->donor_avatar) }}" 
                                             class="rounded-circle me-2"
                                             width="32" height="32"
                                             alt="{{ $donation->donor_name }}">
                                    @else
                                        <div class="rounded-circle bg-secondary-subtle me-2 d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-secondary"></i>
                                        </div>
                                    @endif
                                    {{ $donation->donor_name }}
                                </div>
                            </td>
                            <td>{{ $donation->campaign_title }}</td>
                            <td>${{ number_format($donation->amount, 2) }}</td>
                            <td>{{ $donation->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-success-subtle text-success">
                                    {{ $donation->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 