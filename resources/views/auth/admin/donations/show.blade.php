@extends('layouts.app')

@section('title', 'Donation Details')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Donation Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.donations.index') }}">Donations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($donation->type === 'non_monetary' && $donation->status === 'pending')
                <form action="{{ route('admin.donations.update-status', $donation) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Mark as Received
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Donation Information -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Donation Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="text-muted mb-1">Type</label>
                                <div>
                                    <span class="badge bg-{{ $donation->type === 'monetary' ? 'primary' : 'success' }}">
                                        {{ ucfirst($donation->type) }}
                                    </span>
                                </div>
                            </div>
                            @if($donation->type === 'monetary')
                                <div class="mb-4">
                                    <label class="text-muted mb-1">Amount</label>
                                    <div class="h4">₱{{ number_format($donation->amount, 2) }}</div>
                                </div>
                            @else
                                <div class="mb-4">
                                    <label class="text-muted mb-1">Item</label>
                                    <div class="h4">{{ $donation->item_name }}</div>
                                    <div class="text-muted">{{ $donation->quantity }} units</div>
                                </div>
                            @endif
                            <div class="mb-4">
                                <label class="text-muted mb-1">Status</label>
                                <div>
                                    <span class="badge bg-{{ $donation->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="text-muted mb-1">Date</label>
                                <div>{{ $donation->created_at->format('F d, Y h:ia') }}</div>
                            </div>
                            @if($donation->campaign)
                                <div class="mb-4">
                                    <label class="text-muted mb-1">Campaign</label>
                                    <div>
                                        <a href="{{ route('campaigns.show', $donation->campaign) }}">
                                            {{ $donation->campaign->title }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if($donation->type === 'non_monetary')
                                <div class="mb-4">
                                    <label class="text-muted mb-1">Expected Drop-off Date</label>
                                    <div>
                                        {{ $donation->expected_date->format('F d, Y') }}
                                        @if($donation->expected_date->isPast() && $donation->status === 'pending')
                                            <span class="badge bg-danger ms-2">Overdue</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if($donation->notes)
                            <div class="col-12">
                                <div class="mb-4">
                                    <label class="text-muted mb-1">Notes</label>
                                    <div class="p-3 bg-light rounded">{{ $donation->notes }}</div>
                                </div>
                            </div>
                        @endif
                        @if($donation->image)
                            <div class="col-12">
                                <div class="mb-4">
                                    <label class="text-muted mb-1">Item Image</label>
                                    <div>
                                        <img src="{{ Storage::url($donation->image) }}" 
                                             alt="Item Image" 
                                             class="img-fluid rounded"
                                             style="max-height: 300px;">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Donor Information -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Donor Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x text-muted"></i>
                        </div>
                        <h5 class="mb-1">{{ $donation->is_anonymous ? 'Anonymous' : $donation->donor_name }}</h5>
                        @if(!$donation->is_anonymous)
                            <div class="text-muted">{{ $donation->donor_email }}</div>
                        @endif
                    </div>
                    @if($donation->donor_phone && !$donation->is_anonymous)
                        <div class="mb-3">
                            <label class="text-muted mb-1">Phone</label>
                            <div>{{ $donation->donor_phone }}</div>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="text-muted mb-1">Donation Date</label>
                        <div>{{ $donation->created_at->format('F Y') }}</div>
                    </div>
                    <div>
                        <label class="text-muted mb-1">Donation ID</label>
                        <div class="h4 mb-0">#{{ $donation->id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 