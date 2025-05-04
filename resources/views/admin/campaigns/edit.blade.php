@extends('layouts.app')

@section('title', 'Edit Campaign')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Campaign</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.campaigns.list') }}">Campaigns</a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.campaigns.list') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Back to Campaigns
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $campaign->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="regular" {{ old('type', $campaign->type) === 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="featured" {{ old('type', $campaign->type) === 'featured' ? 'selected' : '' }}>Featured</option>
                                <option value="emergency" {{ old('type', $campaign->type) === 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

                        <div class="mb-3">
                            <label for="goal_amount" class="form-label">Goal Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('goal_amount') is-invalid @enderror" 
                                       id="goal_amount" name="goal_amount" 
                                       value="{{ old('goal_amount', $campaign->goal_amount) }}" required>
                            </div>
                            @error('goal_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

                        <div class="mb-3">
                            <label for="funds_raised" class="form-label">Funds Raised <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('funds_raised') is-invalid @enderror" 
                                       id="funds_raised" name="funds_raised" 
                                       value="{{ old('funds_raised', $campaign->funds_raised) }}" required>
                            </div>
                            @error('funds_raised')
                                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                        </div>
            </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status', $campaign->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="paused" {{ old('status', $campaign->status) === 'paused' ? 'selected' : '' }}>Paused</option>
                </select>
                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" 
                                   value="{{ old('start_date', $campaign->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" 
                                   value="{{ old('end_date', $campaign->end_date->format('Y-m-d')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Campaign Image</label>
                            @if($campaign->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($campaign->image) }}" 
                                         alt="{{ $campaign->title }}" 
                                         class="img-thumbnail"
                                         style="max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">Leave empty to keep the current image. Supported formats: JPEG, PNG, JPG, GIF (max. 2MB)</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description', $campaign->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.campaigns.list') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Campaign</button>
                </div>
            </form>
            </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validate end date is after start date
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    function validateDates() {
        if (startDate.value && endDate.value && startDate.value >= endDate.value) {
            endDate.setCustomValidity('End date must be after start date');
        } else {
            endDate.setCustomValidity('');
        }
    }

    startDate.addEventListener('change', validateDates);
    endDate.addEventListener('change', validateDates);
});
</script>
@endpush
@endsection