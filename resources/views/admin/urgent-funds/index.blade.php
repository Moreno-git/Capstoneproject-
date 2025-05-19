<!-- filepath: c:\CapstoneProject\resources\views\admin\urgent-funds\index.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <!-- Urgent Campaigns Section -->
    <div class="donation-card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Urgent Campaigns</h5>
        </div>
        <div class="dashboard">
            @forelse ($urgentCampaigns ?? [] as $campaign)
                <div class="card mb-3">
                    @if ($campaign->is_urgent)
                        <span class="badge badge-danger">Urgent</span> <!-- Urgent badge -->
                    @endif
                    <h3>{{ $campaign->title }}</h3>
                    <p>{{ $campaign->description }}</p>
                    <p><strong>₱{{ number_format($campaign->raised, 2) }}</strong> of ₱{{ number_format($campaign->goal, 2) }} goal</p>
                    <p>{{ now()->diffInDays($campaign->created_at->addDays(12)) }} days remaining</p>
                </div>
            @empty
                <p class="text-center py-4">No urgent campaigns at the moment.</p>
            @endforelse
        </div>
    </div>

    <!-- All Campaigns Table -->
    <div class="donation-card">
        <div class="card-header">
            <h5 class="mb-0">All Campaigns</h5>
        </div>
        <div class="donation-table-container">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Goal</th>
                            <th>Raised</th>
                            <th>Urgent</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($allCampaigns ?? [] as $campaign)
                            <tr>
                                <td>{{ $campaign->title }}</td>
                                <td>{{ Str::limit($campaign->description, 50) }}</td>
                                <td>₱{{ number_format($campaign->goal, 2) }}</td>
                                <td>₱{{ number_format($campaign->raised, 2) }}</td>
                                <td>
                                    @if ($campaign->is_urgent)
                                        <span class="badge badge-danger">Yes</span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('urgent-campaigns.edit', $campaign->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('urgent-campaigns.destroy', $campaign->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No campaigns found.</td>
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
@endpush