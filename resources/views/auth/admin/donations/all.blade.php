@extends('layouts.app')

@section('title', 'All Donations')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">All Donations</h1>
        <div class="d-flex gap-2">
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
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                Advanced Filters
            </button>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="collapse mb-4" id="advancedFilters">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form id="filterForm" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Date Range</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="start_date">
                            <span class="input-group-text">to</span>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Amount Range</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="min_amount" placeholder="Min">
                            <span class="input-group-text">to</span>
                            <input type="number" class="form-control" name="max_amount" placeholder="Max">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="type">
                            <option value="">All</option>
                            <option value="monetary">Monetary</option>
                            <option value="non_monetary">Non-Monetary</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>
                                <a href="#" class="text-dark text-decoration-none" data-sort="donor">
                                    Donor Name <i class="fas fa-sort"></i>
                                </a>
                            </th>
                            <th>Email</th>
                            <th>
                                <a href="#" class="text-dark text-decoration-none" data-sort="type">
                                    Type <i class="fas fa-sort"></i>
                                </a>
                            </th>
                            <th>
                                <a href="#" class="text-dark text-decoration-none" data-sort="amount">
                                    Amount/Item <i class="fas fa-sort"></i>
                                </a>
                            </th>
                            <th>
                                <a href="#" class="text-dark text-decoration-none" data-sort="status">
                                    Status <i class="fas fa-sort"></i>
                                </a>
                            </th>
                            <th>
                                <a href="#" class="text-dark text-decoration-none" data-sort="date">
                                    Date <i class="fas fa-sort"></i>
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $donation)
                        <tr class="donation-row" data-href="{{ route('admin.donations.show', $donation) }}">
                            <td>{{ $donation->donor->name }}</td>
                            <td>{{ $donation->donor->email }}</td>
                            <td>
                                <span class="badge bg-{{ $donation->type === 'monetary' ? 'primary' : 'success' }}">
                                    {{ ucfirst($donation->type) }}
                                </span>
                            </td>
                            <td>
                                @if($donation->type === 'monetary')
                                    ₱{{ number_format($donation->amount, 2) }}
                                @else
                                    {{ $donation->item_name }} ({{ $donation->quantity }} units)
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $donation->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td>{{ $donation->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
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

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            Showing {{ $donations->firstItem() }} to {{ $donations->lastItem() }} of {{ $donations->total() }} donations
        </div>
        {{ $donations->links() }}
    </div>
</div>

@push('styles')
<style>
    .donation-row {
        cursor: pointer;
    }
    .donation-row:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    th a:hover {
        text-decoration: underline !important;
    }
    .page-link {
        border: none;
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 0.25rem;
    }
    .page-item.active .page-link {
        background-color: #007bff;
    }
</style>
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

    // Advanced filter form submission
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // TODO: Implement AJAX filter submission
        // This would update the table content based on the filter values
    });

    // Sorting functionality
    document.querySelectorAll('[data-sort]').forEach(header => {
        header.addEventListener('click', function(e) {
            e.preventDefault();
            const sortField = this.dataset.sort;
            // TODO: Implement sorting logic
            // This would update the table content based on the sort field
        });
    });
});
</script>
@endpush
@endsection 