@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Campaign Details</h1>
        <a href="{{ route('admin.campaigns.dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Back to Campaigns
        </a>
    </div>

    <div class="row">
        <!-- Campaign Image -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($campaign->image)
                        <img src="{{ asset('storage/' . $campaign->image) }}" 
                             alt="{{ $campaign->title }}" 
                             class="img-fluid rounded"
                             style="width: 100%; height: 400px; object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded" 
                             style="height: 400px;">
                            <i class="fas fa-image text-secondary" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Campaign Info -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Title</h6>
                        <h4 class="mb-0">{{ $campaign->title }}</h4>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Description</h6>
                        <p class="mb-0">{{ $campaign->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Type</h6>
                        <p class="mb-0">{{ $campaign->type }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Status</h6>
                        <span class="badge rounded-pill {{ in_array($campaign->status, ['active', 'ongoing']) ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                            {{ ucfirst($campaign->status) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Funding Progress</h6>
                        @php
                            $percentage = ($campaign->funds_raised / $campaign->goal_amount) * 100;
                            $percentage = min($percentage, 100);
                        @endphp
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $percentage }}%;" 
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-1">Goal Amount</h6>
                                <h4 class="mb-0">₱{{ number_format($campaign->goal_amount, 2) }}</h4>
                            </div>
                            <div class="text-end">
                                <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-1">Funds Raised</h6>
                                <h4 class="mb-0">₱{{ number_format($campaign->funds_raised, 2) }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-1">Start Date</h6>
                                <p class="mb-0">{{ $campaign->start_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-1">End Date</h6>
                                <p class="mb-0">{{ $campaign->end_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .fs-12 {
        font-size: 12px;
    }
</style>
@endpush
@endsection