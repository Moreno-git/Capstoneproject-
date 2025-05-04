@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Create Campaign</h1>
        <a href="{{ route('admin.campaigns.list') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Back to Campaigns
        </a>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.list') }}">Campaigns</a></li>
            <li class="breadcrumb-item active">Create New Campaign</li>
        </ol>
    </nav>

    <!-- Create Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="image" class="form-label">Campaign Image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="form-label">Campaign Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Type -->
                <div class="mb-4">
                    <label for="type" class="form-label">Campaign Type</label>
                    <input type="text" class="form-control @error('type') is-invalid @enderror" 
                           id="type" name="type" value="{{ old('type') }}" 
                           placeholder="e.g. Feeding Program" required>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="paused" {{ old('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <!-- Goal Amount -->
                    <div class="col-md-6 mb-4">
                        <label for="goal_amount" class="form-label">Goal Amount (PHP)</label>
                        <input type="number" class="form-control @error('goal_amount') is-invalid @enderror" 
                               id="goal_amount" name="goal_amount" value="{{ old('goal_amount') }}" 
                               placeholder="e.g. 50000" required>
                        @error('goal_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Initial Funds -->
                    <div class="col-md-6 mb-4">
                        <label for="funds_raised" class="form-label">Initial Funds (PHP)</label>
                        <input type="number" class="form-control @error('funds_raised') is-invalid @enderror" 
                               id="funds_raised" name="funds_raised" value="{{ old('funds_raised', 0) }}" required>
                        @error('funds_raised')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Start Date -->
                    <div class="col-md-6 mb-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div class="col-md-6 mb-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.campaigns.list') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Campaign</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection