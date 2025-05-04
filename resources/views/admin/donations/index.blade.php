@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Donation Management</h2>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Monetary Card -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="donation-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-primary bg-opacity-10">
                                <i class="fas fa-money-bill text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Monetary</h6>
                            <h4 class="mb-0 fw-bold">₱{{ number_format($monetaryTotal, 2) }}</h4>
                            <small class="@if($monetaryChange >= 0) text-success @else text-danger @endif">
                                <i class="fas fa-@if($monetaryChange >= 0)arrow-up @else arrow-down @endif"></i>
                                {{ number_format(abs($monetaryChange), 1) }}% from last month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Non-Monetary Card -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="donation-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-success bg-opacity-10">
                                <i class="fas fa-box-open text-success"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Non-Monetary</h6>
                            <h4 class="mb-0 fw-bold">{{ $nonMonetaryCount }}</h4>
                            <small class="@if($nonMonetaryChange >= 0) text-success @else text-danger @endif">
                                <i class="fas fa-@if($nonMonetaryChange >= 0)arrow-up @else arrow-down @endif"></i>
                                {{ number_format(abs($nonMonetaryChange), 1) }}% from last month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign Card -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="donation-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-info bg-opacity-10">
                                <i class="fas fa-bullhorn text-info"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Campaign</h6>
                            <h4 class="mb-0 fw-bold">₱{{ number_format($campaignTotal, 2) }}</h4>
                            <small class="@if($campaignChange >= 0) text-success @else text-danger @endif">
                                <i class="fas fa-@if($campaignChange >= 0)arrow-up @else arrow-down @endif"></i>
                                {{ number_format(abs($campaignChange), 1) }}% from last month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Donors Card -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="donation-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-warning bg-opacity-10">
                                <i class="fas fa-users text-warning"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Total Donors</h6>
                            <h4 class="mb-0 fw-bold">{{ $donorCount }}</h4>
                            <small class="@if($donorChange >= 0) text-success @else text-danger @endif">
                                <i class="fas fa-@if($donorChange >= 0)arrow-up @else arrow-down @endif"></i>
                                {{ number_format(abs($donorChange), 1) }}% from last month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Donations -->
    <div class="donation-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Recent Donations</h5>
            <div class="search-filter-container">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search donor..." id="donorSearch">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Filter
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" data-filter="all">All</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="monetary">Monetary</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="non_monetary">Non-Monetary</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" data-filter="completed">Completed</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="pending">Pending</a></li>
                    </ul>
                </div>
                <a href="{{ route('admin.donations.all') }}" class="btn btn-primary">Show All</a>
            </div>
        </div>
        <div class="donation-table-container">
            <div class="table-responsive">
                <table class="donation-table">
                    <thead>
                        <tr>
                            <th>Donor Name</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Amount/Item</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $donation)
                        <tr>
                            <td>{{ $donation->donor_name }}</td>
                            <td>{{ $donation->donor_email }}</td>
                            <td>
                                <span class="badge bg-{{ $donation->type === 'monetary' ? 'primary' : 'success' }}">
                                    {{ ucfirst($donation->type) }}
                                </span>
                            </td>
                            <td>
                                @if($donation->type === 'monetary')
                                    <span class="fw-semibold">₱{{ number_format($donation->amount, 2) }}</span>
                                @else
                                    <span class="fw-semibold">{{ $donation->item_name }}</span>
                                    <small class="text-muted d-block">{{ $donation->quantity }} units</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $donation->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td>
                                <span>{{ $donation->created_at?->format('M d, Y') ?? 'N/A' }}</span>
                                <small class="text-muted d-block">{{ $donation->created_at?->format('h:i A') ?? '' }}</small>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.donations.show', $donation) }}">
                                                <i class="fas fa-eye me-2"></i> View Details
                                            </a>
                                        </li>
                                        @if($donation->type === 'non_monetary' && $donation->status === 'pending')
                                        <li>
                                            <form action="{{ route('admin.donations.update-status', $donation) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-check me-2"></i> Mark as Received
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Drop-Off Confirmation Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Drop-Off Confirmation</h5>
            <a href="{{ route('admin.donations.dropoffs') }}" class="btn btn-primary">
                Manage Drop-Offs
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Item</th>
                            <th>Expected Drop-off Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingDropoffs as $dropoff)
                        <tr>
                            <td>{{ $dropoff->item_name }} - {{ $dropoff->quantity }} units</td>
                            <td>{{ $dropoff->expected_date?->format('M d, Y') ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-warning">Pending</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.donations.update-status', $dropoff) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check me-1"></i> Confirm Receipt
                                    </button>
                                </form>
                                <form action="{{ route('admin.donations.update-status', $dropoff) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times me-1"></i> Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/donation-panel.css') }}">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Make donation rows clickable
    document.querySelectorAll('.donation-row').forEach(row => {
        row.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                window.location.href = this.dataset.href;
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('donorSearch');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('.donation-row').forEach(row => {
            const donorName = row.querySelector('td:first-child').textContent.toLowerCase();
            const donorEmail = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            row.style.display = donorName.includes(searchTerm) || donorEmail.includes(searchTerm) ? '' : 'none';
        });
    });

    // Filter functionality
    document.querySelectorAll('[data-filter]').forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            const filterValue = this.dataset.filter;
            document.querySelectorAll('.donation-row').forEach(row => {
                if (filterValue === 'all') {
                    row.style.display = '';
                    return;
                }
                const type = row.querySelector('td:nth-child(3) .badge').textContent.toLowerCase();
                const status = row.querySelector('td:nth-child(5) .badge').textContent.toLowerCase();
                row.style.display = (type === filterValue || status === filterValue) ? '' : 'none';
            });
        });
    });
});
</script>
@endpush
@endsection 