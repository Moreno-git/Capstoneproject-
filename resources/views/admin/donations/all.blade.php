@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">All Donations</h1>
            <p class="text-muted mb-0">Comprehensive list of all donations</p>
        </div>
        <div class="d-flex gap-2">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search donations..." 
                       name="search" value="{{ request('search') }}">
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
            <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Monetary Donations -->
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <span class="bg-primary bg-opacity-10 p-2 rounded">
                                <i class="fas fa-dollar-sign text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Monetary</h6>
                        </div>
                    </div>
                    <h3 class="mb-2">₱{{ number_format($monetaryTotal ?? 0, 2) }}</h3>
                    <div class="small {{ ($monetaryChange ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas fa-{{ ($monetaryChange ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                        {{ abs($monetaryChange ?? 0) }}% from last month
                    </div>
                </div>
            </div>
        </div>

        <!-- Non-Monetary Donations -->
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <span class="bg-warning bg-opacity-10 p-2 rounded">
                                <i class="fas fa-box text-warning"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Non-Monetary</h6>
                        </div>
                    </div>
                    <h3 class="mb-2">{{ $nonMonetaryCount ?? 0 }}</h3>
                    <div class="small {{ ($nonMonetaryChange ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas fa-{{ ($nonMonetaryChange ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                        {{ abs($nonMonetaryChange ?? 0) }}% from last month
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign Donations -->
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <span class="bg-info bg-opacity-10 p-2 rounded">
                                <i class="fas fa-bullhorn text-info"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Campaign</h6>
                        </div>
                    </div>
                    <h3 class="mb-2">₱{{ number_format($campaignTotal ?? 0, 2) }}</h3>
                    <div class="small {{ ($campaignChange ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas fa-{{ ($campaignChange ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                        {{ abs($campaignChange ?? 0) }}% from last month
                    </div>
                </div>
            </div>
        </div>

        <!-- Donors -->
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <span class="bg-success bg-opacity-10 p-2 rounded">
                                <i class="fas fa-users text-success"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Donors</h6>
                        </div>
                    </div>
                    <h3 class="mb-2">{{ $donorCount ?? 0 }}</h3>
                    <div class="small {{ ($donorChange ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas fa-{{ ($donorChange ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                        {{ abs($donorChange ?? 0) }}% from last month
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Donor</th>
                            <th>Type</th>
                            <th>Amount/Item</th>
                            <th>Campaign</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $donation)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <img src="{{ asset('images/default-avatar.png') }}" 
                                             class="rounded-circle" alt="Avatar">
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $donation->donor_name }}</div>
                                        <div class="small text-muted">{{ $donation->donor_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $donation->type === 'monetary' ? 'primary' : 'warning' }}">
                                    {{ ucfirst($donation->type) }}
                                </span>
                            </td>
                            <td>
                                @if($donation->type === 'monetary')
                                    <div class="fw-medium">₱{{ number_format($donation->amount, 2) }}</div>
                                    <div class="small text-muted">{{ ucfirst($donation->payment_method) }}</div>
                                @else
                                    <div class="fw-medium">{{ $donation->item_description }}</div>
                                    <div class="small text-muted">{{ $donation->quantity }} units</div>
                                @endif
                            </td>
                            <td>
                                @if($donation->campaign)
                                    <span class="badge bg-info">{{ $donation->campaign->title }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $donation->status_color }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td>
                                @if($donation->created_at)
                                    <div>{{ $donation->created_at->format('M d, Y') }}</div>
                                    <div class="small text-muted">{{ $donation->created_at->format('h:i A') }}</div>
                                @else
                                    <div class="text-muted">No date</div>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-link" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.donations.show', $donation) }}">
                                                <i class="fas fa-eye me-2"></i> View Details
                                            </a>
                                        </li>
                                        @if($donation->type === 'non-monetary' && $donation->status === 'pending')
                                        <li>
                                            <a class="dropdown-item" href="#" 
                                               onclick="updateStatus('{{ $donation->id }}', 'completed')">
                                                <i class="fas fa-check me-2"></i> Mark as Received
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">No donations found</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($donations->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $donations->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Filter Donations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.donations.all') }}" method="GET" id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="type">
                            <option value="">All Types</option>
                            <option value="monetary" {{ request('type') === 'monetary' ? 'selected' : '' }}>
                                Monetary
                            </option>
                            <option value="non-monetary" {{ request('type') === 'non-monetary' ? 'selected' : '' }}>
                                Non-Monetary
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Campaign</label>
                        <select class="form-select" name="campaign_id">
                            <option value="">All Campaigns</option>
                            @foreach($campaigns as $id => $title)
                            <option value="{{ $id }}" {{ request('campaign_id') == $id ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="filterForm" class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Search functionality with debounce
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });

    // Update donation status
    function updateStatus(donationId, status) {
        if (confirm('Are you sure you want to mark this donation as received?')) {
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