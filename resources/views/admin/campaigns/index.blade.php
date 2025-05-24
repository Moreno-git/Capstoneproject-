@extends('layouts.app')

@section('title', 'Manage Campaigns')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Campaigns</h2>
        <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Campaign
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Type</th>
                            <th>Goal</th>
                            <th>Raised</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $campaign)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($campaign->image)
                                            <img src="{{ Storage::url($campaign->image) }}" 
                                                 alt="{{ $campaign->title }}" 
                                                 class="rounded me-3"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-bullhorn text-secondary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $campaign->title }}</h6>
                                            <small class="text-muted">{{ Str::limit($campaign->description, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ ucfirst($campaign->type) }}</td>
                                <td>₱{{ number_format($campaign->goal_amount, 2) }}</td>
                                <td>₱{{ number_format($campaign->funds_raised, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $campaign->status === 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.campaigns.show', $campaign) }}" 
                                           class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.campaigns.edit', $campaign) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteCampaignModal{{ $campaign->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Campaign Modal -->
                                    <div class="modal fade" id="deleteCampaignModal{{ $campaign->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">Delete Campaign</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center py-4">
                                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                                    <h4>Are you sure?</h4>
                                                    <p class="text-muted">Do you really want to delete this campaign? This process cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 