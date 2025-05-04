@extends('layouts.app')

@section('title', 'Manage Drop-offs')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Drop-offs</h1>
        <div class="d-flex gap-2">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search items..." id="itemSearch">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Status
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" data-status="all">All</a></li>
                    <li><a class="dropdown-item" href="#" data-status="pending">Pending</a></li>
                    <li><a class="dropdown-item" href="#" data-status="completed">Completed</a></li>
                    <li><a class="dropdown-item" href="#" data-status="rejected">Rejected</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="dropoffTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                Pending Drop-offs
                <span class="badge bg-warning ms-2">{{ $pendingDropOffs->total() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                Completed Drop-offs
                <span class="badge bg-success ms-2">{{ $completedDropOffs->total() }}</span>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="dropoffTabsContent">
        <!-- Pending Drop-offs -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item Details</th>
                                    <th>Donor Information</th>
                                    <th>Expected Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingDropOffs as $dropoff)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($dropoff->image)
                                                <img src="{{ Storage::url($dropoff->image) }}" 
                                                     alt="Item Image" 
                                                     class="rounded me-3"
                                                     style="width: 48px; height: 48px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                     style="width: 48px; height: 48px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $dropoff->item_name }}</h6>
                                                <small class="text-muted">{{ $dropoff->quantity }} units</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $dropoff->donor->name }}</h6>
                                            <small class="text-muted">{{ $dropoff->donor->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $dropoff->expected_date->format('M d, Y') }}
                                        @if($dropoff->expected_date->isPast())
                                            <span class="badge bg-danger ms-2">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">Pending</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <form action="{{ route('admin.donations.update-status', $dropoff) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-sm btn-success me-2">
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
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    Showing {{ $pendingDropOffs->firstItem() }} to {{ $pendingDropOffs->lastItem() }} of {{ $pendingDropOffs->total() }} pending drop-offs
                </div>
                {{ $pendingDropOffs->links() }}
            </div>
        </div>

        <!-- Completed Drop-offs -->
        <div class="tab-pane fade" id="completed" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item Details</th>
                                    <th>Donor Information</th>
                                    <th>Drop-off Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedDropOffs as $dropoff)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($dropoff->image)
                                                <img src="{{ Storage::url($dropoff->image) }}" 
                                                     alt="Item Image" 
                                                     class="rounded me-3"
                                                     style="width: 48px; height: 48px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                     style="width: 48px; height: 48px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $dropoff->item_name }}</h6>
                                                <small class="text-muted">{{ $dropoff->quantity }} units</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $dropoff->donor->name }}</h6>
                                            <small class="text-muted">{{ $dropoff->donor->email }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $dropoff->updated_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $dropoff->status === 'completed' ? 'success' : 'danger' }}">
                                            {{ ucfirst($dropoff->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.donations.show', $dropoff) }}" class="btn btn-sm btn-light">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    Showing {{ $completedDropOffs->firstItem() }} to {{ $completedDropOffs->lastItem() }} of {{ $completedDropOffs->total() }} completed drop-offs
                </div>
                {{ $completedDropOffs->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
    }
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        padding: 1rem 1.5rem;
        color: #6c757d;
        font-weight: 500;
    }
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #007bff;
    }
    .nav-tabs .nav-link.active {
        color: #007bff;
        border-bottom-color: #007bff;
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
    // Search functionality
    const searchInput = document.getElementById('itemSearch');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const itemName = row.querySelector('h6').textContent.toLowerCase();
            const donorName = row.querySelector('td:nth-child(2) h6').textContent.toLowerCase();
            row.style.display = itemName.includes(searchTerm) || donorName.includes(searchTerm) ? '' : 'none';
        });
    });

    // Status filter
    document.querySelectorAll('[data-status]').forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            const status = this.dataset.status;
            document.querySelectorAll('tbody tr').forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                    return;
                }
                const rowStatus = row.querySelector('td:nth-child(4) .badge').textContent.toLowerCase();
                row.style.display = rowStatus === status ? '' : 'none';
            });
        });
    });
});
</script>
@endpush
@endsection 