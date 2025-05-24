<!-- filepath: c:\CapstoneProject\resources\views\admin\urgent-funds\index.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <!-- Create Button -->
    <div class="mb-4">
        <a href="{{ route('admin.urgent-funds.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Create New Campaign
        </a>
    </div>

    <!-- Urgent Campaigns Section -->
    <div class="donation-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Urgent Campaigns</h5>
            <span class="badge bg-danger">Priority</span>
        </div>
        <div class="dashboard">
            @forelse ($urgentCampaigns ?? [] as $campaign)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                @if ($campaign->is_urgent)
                                    <span class="badge bg-danger mb-2">Urgent</span>
                                @endif
                                <h3 class="card-title h5">{{ $campaign->title }}</h3>
                                <p class="card-text text-muted">{{ Str::limit($campaign->description, 150) }}</p>
                                
                                <div class="progress mb-3" style="height: 10px;">
                                    @php
                                        $progress = ($campaign->funds_raised / $campaign->goal_amount) * 100;
                                    @endphp
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ min($progress, 100) }}%" 
                                         aria-valuenow="{{ $progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">Raised: ₱{{ number_format($campaign->funds_raised, 2) }}</small>
                                    <small class="text-muted">Goal: ₱{{ number_format($campaign->goal_amount, 2) }}</small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> 
                                        {{ now()->diffInDays($campaign->created_at->addDays(30)) }} days remaining
                                    </small>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.urgent-funds.edit', $campaign->id) }}" 
                                           class="btn btn-sm btn-warning me-2">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.urgent-funds.destroy', $campaign->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No urgent campaigns at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- All Campaigns Table -->
    <div class="donation-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Campaigns</h5>
            <span class="badge bg-primary">Total: {{ $allCampaigns->count() }}</span>
        </div>
        <div class="donation-table-container">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Goal</th>
                            <th>Raised</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($allCampaigns ?? [] as $campaign)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($campaign->is_urgent)
                                            <span class="badge bg-danger me-2">Urgent</span>
                                        @endif
                                        {{ $campaign->title }}
                                    </div>
                                </td>
                                <td>{{ Str::limit($campaign->description, 50) }}</td>
                                <td>₱{{ number_format($campaign->goal_amount, 2) }}</td>
                                <td>₱{{ number_format($campaign->funds_raised, 2) }}</td>
                                <td style="width: 150px;">
                                    <div class="progress" style="height: 5px;">
                                        @php
                                            $progress = ($campaign->funds_raised / $campaign->goal_amount) * 100;
                                        @endphp
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ min($progress, 100) }}%" 
                                             aria-valuenow="{{ $progress }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ number_format($progress, 1) }}%</small>
                                </td>
                                <td>
                                    @if ($campaign->is_urgent)
                                        <span class="badge bg-danger">Urgent</span>
                                    @else
                                        <span class="badge bg-secondary">Normal</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.urgent-funds.edit', $campaign->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit Campaign">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.urgent-funds.destroy', $campaign->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Delete Campaign">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No campaigns found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/donation-panel.css') }}">
<style>
    .progress {
        background-color: #e9ecef;
        border-radius: 0.25rem;
    }
    .btn-group {
        gap: 0.25rem;
    }
    .table > :not(caption) > * > * {
        padding: 1rem;
    }
    .badge {
        font-weight: 500;
    }
    .gap-2 {
        gap: 0.5rem;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush