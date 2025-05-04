@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Drop-Off Donations</h1>
            <p class="text-muted mb-0">Manage and track non-monetary donations</p>
        </div>
        <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="donationTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" 
                    data-bs-target="#pending" type="button" role="tab">
                Pending Drop-offs
                <span class="badge bg-warning ms-2">{{ $pendingDropoffs->total() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" 
                    data-bs-target="#completed" type="button" role="tab">
                Completed Drop-offs
                <span class="badge bg-success ms-2">{{ $completedDropoffs->total() }}</span>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="donationTabsContent">
        <!-- Pending Drop-offs -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item Details</th>
                                    <th>Donor</th>
                                    <th>Campaign</th>
                                    <th>Expected Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingDropoffs as $dropoff)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-box text-primary"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-medium">{{ $dropoff->item_description }}</div>
                                                <div class="small text-muted">{{ $dropoff->quantity }} units</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <img src="{{ $dropoff->donor->avatar_url ?? asset('images/default-avatar.png') }}" 
                                                     class="rounded-circle" alt="Avatar">
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $dropoff->donor_name }}</div>
                                                <div class="small text-muted">{{ $dropoff->donor_email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($dropoff->campaign)
                                            <span class="badge bg-info">{{ $dropoff->campaign->title }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $dropoff->expected_date?->format('M d, Y') ?? 'Not specified' }}</td>
                                    <td>
                                        <span class="badge bg-warning">Pending</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <button class="btn btn-success btn-sm" 
                                                    onclick="updateDropoffStatus('{{ $dropoff->id }}', 'completed')">
                                                <i class="fas fa-check me-1"></i> Mark as Received
                                            </button>
                                            <button class="btn btn-danger btn-sm" 
                                                    onclick="updateDropoffStatus('{{ $dropoff->id }}', 'cancelled')">
                                                <i class="fas fa-times me-1"></i> Cancel
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">No pending drop-offs</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($pendingDropoffs->hasPages())
                <div class="card-footer bg-white border-0">
                    {{ $pendingDropoffs->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Completed Drop-offs -->
        <div class="tab-pane fade" id="completed" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item Details</th>
                                    <th>Donor</th>
                                    <th>Campaign</th>
                                    <th>Received Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($completedDropoffs as $dropoff)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-box text-success"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-medium">{{ $dropoff->item_description }}</div>
                                                <div class="small text-muted">{{ $dropoff->quantity }} units</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <img src="{{ $dropoff->donor->avatar_url ?? asset('images/default-avatar.png') }}" 
                                                     class="rounded-circle" alt="Avatar">
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $dropoff->donor_name }}</div>
                                                <div class="small text-muted">{{ $dropoff->donor_email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($dropoff->campaign)
                                            <span class="badge bg-info">{{ $dropoff->campaign->title }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $dropoff->updated_at?->format('M d, Y') ?? 'Not available' }}</td>
                                    <td>
                                        <span class="badge bg-success">Completed</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.donations.show', $dropoff) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">No completed drop-offs</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($completedDropoffs->hasPages())
                <div class="card-footer bg-white border-0">
                    {{ $completedDropoffs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateDropoffStatus(donationId, status) {
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