@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Donation Details</h1>
            <p class="text-muted mb-0">View complete donation information</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
            @if($donation->type === 'non-monetary' && $donation->status === 'pending')
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-cog me-2"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#" 
                           onclick="updateStatus('{{ $donation->id }}', 'completed')">
                            <i class="fas fa-check me-2"></i> Mark as Received
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" 
                           onclick="updateStatus('{{ $donation->id }}', 'cancelled')">
                            <i class="fas fa-times me-2"></i> Cancel Donation
                        </a>
                    </li>
                </ul>
            </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Information -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Donation Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="small text-muted d-block">Type</label>
                                <span class="badge bg-{{ $donation->type === 'monetary' ? 'primary' : 'warning' }} fs-6">
                                    {{ ucfirst($donation->type) }}
                                </span>
                            </div>
                            @if($donation->type === 'monetary')
                            <div class="mb-4">
                                <label class="small text-muted d-block">Amount</label>
                                <h3 class="mb-0">₱{{ number_format($donation->amount, 2) }}</h3>
                            </div>
                            <div class="mb-4">
                                <label class="small text-muted d-block">Payment Method</label>
                                <span class="badge bg-info">{{ ucfirst($donation->payment_method) }}</span>
                            </div>
                            @if($donation->transaction_id)
                            <div class="mb-4">
                                <label class="small text-muted d-block">Transaction ID</label>
                                <code>{{ $donation->transaction_id }}</code>
                            </div>
                            @endif
                            @else
                            <div class="mb-4">
                                <label class="small text-muted d-block">Item Description</label>
                                <h5 class="mb-1">{{ $donation->item_description }}</h5>
                                <span class="text-muted">{{ $donation->quantity }} units</span>
                            </div>
                            @if($donation->expected_date)
                            <div class="mb-4">
                                <label class="small text-muted d-block">Expected Drop-off Date</label>
                                <span>{{ $donation->expected_date->format('F d, Y') }}</span>
                            </div>
                            @endif
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="small text-muted d-block">Status</label>
                                <span class="badge bg-{{ $donation->status_color }} fs-6">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <label class="small text-muted d-block">Date Created</label>
                                <div>{{ $donation->created_at->format('F d, Y') }}</div>
                                <small class="text-muted">{{ $donation->created_at->format('h:i A') }}</small>
                            </div>
                            @if($donation->status === 'completed')
                            <div class="mb-4">
                                <label class="small text-muted d-block">Date Completed</label>
                                <div>{{ $donation->updated_at->format('F d, Y') }}</div>
                                <small class="text-muted">{{ $donation->updated_at->format('h:i A') }}</small>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($donation->notes)
                    <div class="mt-4">
                        <label class="small text-muted d-block">Additional Notes</label>
                        <div class="p-3 bg-light rounded">
                            {{ $donation->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Donor Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Donor Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar avatar-lg me-3">
                            <img src="{{ $donation->donor->avatar_url ?? asset('images/default-avatar.png') }}" 
                                 class="rounded-circle" alt="Avatar">
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $donation->donor_name }}</h5>
                            <div class="text-muted">
                                <i class="fas fa-envelope me-2"></i> {{ $donation->donor_email }}
                            </div>
                            @if($donation->donor_phone)
                            <div class="text-muted">
                                <i class="fas fa-phone me-2"></i> {{ $donation->donor_phone }}
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($donation->donor->total_donations > 1)
                    <div class="alert alert-info mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 fs-4"></i>
                            <div>
                                <strong>Recurring Donor</strong>
                                <div class="small">
                                    Has made {{ $donation->donor->total_donations }} donations in total
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Campaign Information -->
            @if($donation->campaign)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Campaign Information</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-3">{{ $donation->campaign->title }}</h5>
                    <div class="mb-3 text-muted small">
                        {{ Str::limit($donation->campaign->description, 100) }}
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">Campaign Status</div>
                        <span class="badge bg-{{ $donation->campaign->is_active ? 'success' : 'danger' }}">
                            {{ $donation->campaign->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col">
                            <div class="small text-muted mb-1">Total Raised</div>
                            <div class="fw-medium">₱{{ number_format($donation->campaign->total_amount, 2) }}</div>
                        </div>
                        <div class="col">
                            <div class="small text-muted mb-1">Goal</div>
                            <div class="fw-medium">₱{{ number_format($donation->campaign->goal_amount, 2) }}</div>
                        </div>
                        <div class="col">
                            <div class="small text-muted mb-1">Progress</div>
                            <div class="fw-medium">{{ $donation->campaign->progress }}%</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateStatus(donationId, status) {
        const action = status === 'completed' ? 'mark this donation as received' : 'cancel this donation';
        if (confirm(`Are you sure you want to ${action}?`)) {
            fetch(`/admin/donations/${donationId}/dropoff-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }
    }
</script>
@endpush 