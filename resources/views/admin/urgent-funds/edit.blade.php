@extends('layouts.app')

@section('title', 'Edit Urgent Campaign')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Urgent Campaign</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.urgent-funds.update', $campaign->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label">Campaign Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $campaign->title) }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $campaign->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="goal" class="form-label">Goal Amount (₱)</label>
                    <input type="number" name="goal" id="goal" class="form-control" value="{{ old('goal', $campaign->goal) }}" min="0" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="raised" class="form-label">Raised Amount (₱)</label>
                    <input type="number" name="raised" id="raised" class="form-control" value="{{ old('raised', $campaign->raised) }}" min="0" step="0.01">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_urgent" id="is_urgent" class="form-check-input" {{ $campaign->is_urgent ? 'checked' : '' }}>
                    <label for="is_urgent" class="form-check-label">Mark as Urgent</label>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Campaign
                </button>
                <a href="{{ route('admin.urgent-funds.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </form>
        </div>
    </div>
</div>
@endsection