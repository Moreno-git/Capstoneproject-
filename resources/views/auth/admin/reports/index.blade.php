@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reports & Analytics</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports.export', ['format' => 'excel']) }}" 
               class="btn btn-light-success d-flex align-items-center gap-2">
                <i class="fas fa-file-excel"></i>
                <span>Export Excel</span>
            </a>
            <a href="{{ route('admin.reports.export', ['format' => 'pdf']) }}" 
               class="btn btn-light-danger d-flex align-items-center gap-2">
                <i class="fas fa-file-pdf"></i>
                <span>Export PDF</span>
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-primary bg-opacity-10">
                                <i class="fas fa-hand-holding-dollar text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Total Donations</h6>
                            <h4 class="mb-0 fw-bold">₱{{ number_format($totalMonetaryDonations, 2) }}</h4>
                            <small class="text-muted">+ {{ $totalNonMonetaryDonations }} non-monetary items</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-success bg-opacity-10">
                                <i class="fas fa-users text-success"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Total Donors</h6>
                            <h4 class="mb-0 fw-bold">{{ $totalDonors }}</h4>
                            <small class="text-muted">+ {{ $newDonors }} new this month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-info bg-opacity-10">
                                <i class="fas fa-bullhorn text-info"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Active Campaigns</h6>
                            <h4 class="mb-0 fw-bold">{{ $activeCampaigns }}</h4>
                            <small class="text-muted">{{ $completedCampaigns }} completed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="p-3 rounded-circle bg-warning bg-opacity-10">
                                <i class="fas fa-house-heart text-warning"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase fs-12 fw-semibold mb-2">Families Helped</h6>
                            <h4 class="mb-0 fw-bold">{{ $familiesHelped }}</h4>
                            <small class="text-muted">Through all campaigns</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Donations Over Time</h5>
                </div>
                <div class="card-body">
                    <canvas id="donationsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Top Performing Campaigns</h5>
                </div>
                <div class="card-body">
                    <canvas id="campaignsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-light-success {
        color: #198754;
        background-color: rgba(25, 135, 84, 0.1);
        border: none;
    }
    .btn-light-success:hover {
        background-color: rgba(25, 135, 84, 0.2);
        color: #198754;
    }
    .btn-light-danger {
        color: #dc3545;
        background-color: rgba(220, 53, 69, 0.1);
        border: none;
    }
    .btn-light-danger:hover {
        background-color: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    // Donations Over Time Chart
    const donationsCtx = document.getElementById('donationsChart').getContext('2d');
    new Chart(donationsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($donationsOverTime->pluck('date')) !!},
            datasets: [{
                label: 'Donations',
                data: {!! json_encode($donationsOverTime->pluck('amount')) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [2, 2]
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Top Campaigns Chart
    const campaignsCtx = document.getElementById('campaignsChart').getContext('2d');
    new Chart(campaignsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topCampaigns->pluck('title')) !!},
            datasets: [{
                label: 'Donations',
                data: {!! json_encode($topCampaigns->pluck('donations_sum_amount')) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.5)',
                borderColor: '#0d6efd',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [2, 2]
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection 