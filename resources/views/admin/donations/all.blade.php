@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Dynamic Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                @if(request('type') === 'monetary')
                    Monetary Donations
                @elseif(request('type') === 'non_monetary')
                    Non-Monetary Donations
                @elseif(request('status') === 'completed')
                    Completed Donations
                @elseif(request('status') === 'pending')
                    Pending Donations
                @else
                    All Donations
                @endif
            </h1>
            <p class="text-muted mb-0">
                @if(request('type') === 'monetary')
                    List of all monetary donations
                @elseif(request('type') === 'non_monetary')
                    List of all non-monetary donations
                @elseif(request('status') === 'completed')
                    List of all completed donations
                @elseif(request('status') === 'pending')
                    List of all pending donations
                @else
                    Comprehensive list of all donations
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="donation-card mb-4">
        <div class="card-body">
            <form id="searchForm" action="{{ route('admin.donations.all') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" 
                               placeholder="Search by donor name, email..." 
                               name="search" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="type">
                        <option value="">All Types</option>
                        <option value="monetary" {{ request('type') === 'monetary' ? 'selected' : '' }}>Monetary</option>
                        <option value="non_monetary" {{ request('type') === 'non_monetary' ? 'selected' : '' }}>Non-Monetary</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="campaign_id">
                        <option value="">All Campaigns</option>
                        @foreach($campaigns as $id => $title)
                            <option value="{{ $id }}" {{ request('campaign_id') == $id ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-2"></i> Apply Filters
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="resetFilters">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="donation-card">
        <div class="donation-table-container">
            <table class="donation-table">
                <thead>
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
                                <div class="avatar-sm me-3">
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
                            <div class="fw-medium">{{ $donation->created_at->format('M d, Y') }}</div>
                            <div class="small text-muted">{{ $donation->created_at->format('h:i A') }}</div>
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
                                    @if($donation->type === 'non-monetary' && $donation->status === 'pending')
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
        @if($donations->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $donations->firstItem() }} to {{ $donations->lastItem() }} 
                    of {{ $donations->total() }} donations
                </div>
                {{ $donations->appends(request()->except('page'))->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/donations.js') }}"></script>
<script>
    // Reset filters functionality
    document.getElementById('resetFilters').addEventListener('click', function() {
        document.querySelectorAll('#searchForm input, #searchForm select').forEach(element => {
            element.value = '';
        });
        document.getElementById('searchForm').dispatchEvent(new Event('submit'));
    });
</script>
@endpush 