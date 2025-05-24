@extends('layouts.admin')

@section('title', 'Create Urgent Campaign')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Create Urgent Campaign</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.urgent-funds.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Campaign Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="goal_amount" class="form-label">Goal Amount (₱)</label>
                    <input type="number" name="goal_amount" id="goal_amount" class="form-control" value="{{ old('goal_amount') }}" min="0" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="funds_raised" class="form-label">Raised Amount (₱)</label>
                    <input type="number" name="funds_raised" id="funds_raised" class="form-control" value="{{ old('funds_raised', 0) }}" min="0" step="0.01">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Campaign
                </button>
                <a href="{{ route('admin.urgent-funds.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </form>
        </div>
    </div>
</div>
@endsection