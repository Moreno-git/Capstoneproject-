@extends('layouts.app')

@section('title', 'Reports Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Reports Dashboard</h1>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <a href="{{ route('admin.reports.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Export to Excel
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Donations</h5>
                    <h2 class="mb-0">{{ $donations->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Active Campaigns</h5>
                    <h2 class="mb-0">{{ $campaigns->where('status', 'active')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Donors</h5>
                    <h2 class="mb-0">{{ $donations->pluck('donor_id')->unique()->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Pending Donations</h5>
                    <h2 class="mb-0">{{ $donations->where('status', 'pending')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaign Performance -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Campaign Performance</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Donations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->title }}</td>
                            <td>
                                <span class="badge bg-{{ $campaign->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </td>
                            <td>{{ $campaign->start_date->format('M d, Y') }}</td>
                            <td>{{ $campaign->end_date->format('M d, Y') }}</td>
                            <td>{{ $campaign->donations_count }}</td>
                            <td>
                                <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Donations -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Recent Donations</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Donor</th>
                            <th>Campaign</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations->take(10) as $donation)
                        <tr>
                            <td>{{ $donation->donor->name }}</td>
                            <td>{{ $donation->campaign->title }}</td>
                            <td>{{ $donation->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $donation->status === 'completed' ? 'success' : ($donation->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($donation->type) }}</td>
                            <td>
                                <a href="{{ route('admin.donations.show', $donation) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Reports</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.reports.index') }}" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="dateRange" class="form-label">Date Range</label>
                        <select class="form-select" id="dateRange" name="date_range">
                            <option value="7" {{ request('date_range') == 7 ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="30" {{ request('date_range') == 30 ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="90" {{ request('date_range') == 90 ? 'selected' : '' }}>Last 3 Months</option>
                            <option value="180" {{ request('date_range') == 180 ? 'selected' : '' }}>Last 6 Months</option>
                            <option value="365" {{ request('date_range') == 365 ? 'selected' : '' }}>Last Year</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="campaignFilter" class="form-label">Campaign</label>
                        <select class="form-select" id="campaignFilter" name="campaign_id">
                            <option value="">All Campaigns</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card-title {
        color: #333;
        font-weight: 600;
    }
    .table th {
        font-weight: 600;
        color: #333;
    }
    .badge {
        font-weight: 500;
    }
</style>
@endpush 