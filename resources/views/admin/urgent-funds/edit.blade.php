@extends('layouts.admin')

@section('title', 'Edit Urgent Campaign')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Campaign
                    </h5>
                </div>
                <div class="card-body">
                    <div id="alert-container"></div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="editCampaignForm" action="{{ route('admin.urgent-funds.update', $campaign->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">Campaign Title</label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $campaign->title) }}" 
                                   required
                                   placeholder="Enter campaign title">
                            <div class="form-text">Give your campaign a clear and compelling title</div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="4"
                                      placeholder="Describe your campaign's purpose and goals">{{ old('description', $campaign->description) }}</textarea>
                            <div class="form-text">Provide detailed information about your campaign</div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="goal_amount" class="form-label fw-bold">Goal Amount (₱)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" 
                                           name="goal_amount" 
                                           id="goal_amount" 
                                           class="form-control @error('goal_amount') is-invalid @enderror" 
                                           value="{{ old('goal_amount', $campaign->goal_amount) }}" 
                                           min="0" 
                                           step="0.01" 
                                           required
                                           placeholder="0.00">
                                </div>
                                <div class="form-text">Set your fundraising target</div>
                            </div>
                            <div class="col-md-6">
                                <label for="funds_raised" class="form-label fw-bold">Raised Amount (₱)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" 
                                           name="funds_raised" 
                                           id="funds_raised" 
                                           class="form-control @error('funds_raised') is-invalid @enderror" 
                                           value="{{ old('funds_raised', $campaign->funds_raised) }}" 
                                           min="0" 
                                           step="0.01"
                                           placeholder="0.00">
                                </div>
                                <div class="form-text">Current amount raised</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.urgent-funds.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Campaign
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-label {
        color: #2c3e50;
    }
    .form-text {
        color: #6c757d;
        font-size: 0.875rem;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    .form-control {
        border-left: none;
    }
    .form-control:focus {
        box-shadow: none;
        border-color: #ced4da;
    }
    .form-control:focus + .input-group-text {
        border-color: #ced4da;
    }
    .card {
        border: none;
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    .btn {
        padding: 0.5rem 1.5rem;
        font-weight: 500;
    }
    .alert {
        border-radius: 8px;
    }
    .auto-save-indicator {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 5px;
        background-color: #28a745;
        color: white;
        display: none;
        z-index: 1000;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editCampaignForm');
    const alertContainer = document.getElementById('alert-container');
    let autoSaveTimeout;
    let isDirty = false;
    let isSaving = false;

    // Create auto-save indicator
    const autoSaveIndicator = document.createElement('div');
    autoSaveIndicator.className = 'auto-save-indicator';
    autoSaveIndicator.innerHTML = '<i class="fas fa-save me-2"></i>Changes saved automatically';
    document.body.appendChild(autoSaveIndicator);

    // Function to show alert
    function showAlert(message, type = 'success') {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alert);
    }

    // Function to show auto-save indicator
    function showAutoSaveIndicator() {
        autoSaveIndicator.style.display = 'block';
        setTimeout(() => {
            autoSaveIndicator.style.display = 'none';
        }, 2000);
    }

    // Function to handle form submission
    async function submitForm(event) {
        if (event) event.preventDefault();
        if (isSaving) return;
        
        isSaving = true;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        submitButton.disabled = true;

        try {
            const formData = new FormData(form);
            formData.append('is_urgent', '1'); // Always set is_urgent to true
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (response.ok) {
                showAlert('Campaign updated successfully!');
                showAutoSaveIndicator();
                isDirty = false;
            } else {
                showAlert(data.message || 'Error updating campaign', 'danger');
            }
        } catch (error) {
            showAlert('Error updating campaign: ' + error.message, 'danger');
        } finally {
            isSaving = false;
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
        }
    }

    // Add input event listeners to all form fields
    form.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', () => {
            isDirty = true;
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(submitForm, 2000);
        });
    });

    // Handle form submission
    form.addEventListener('submit', submitForm);

    // Handle page unload if there are unsaved changes
    window.addEventListener('beforeunload', (e) => {
        if (isDirty) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
});
</script>
@endpush