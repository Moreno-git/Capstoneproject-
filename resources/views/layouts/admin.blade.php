<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - DonationAdmin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- YouTube-style Sidebar -->
    <div class="yt-sidebar-overlay"></div>
    <div class="yt-sidebar">
        <div class="yt-sidebar-header">
            <span class="fw-bold">DonationAdmin</span>
        </div>
        <div class="yt-sidebar-content">
            <ul class="yt-sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="yt-sidebar-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.donations.index') }}" class="yt-sidebar-menu-item {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding-heart"></i>
                        <span>Donations</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.campaigns.dashboard') }}" class="yt-sidebar-menu-item {{ request()->routeIs('admin.campaigns.*') ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i>
                        <span>Campaigns</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.calendar.index') }}" class="yt-sidebar-menu-item {{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar"></i>
                        <span>Calendar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reports.index') }}" class="yt-sidebar-menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="yt-sidebar-menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.urgent-funds.index') }}" class="yt-sidebar-menu-item {{ request()->routeIs('admin.urgent-funds.*') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Urgent Funds</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Top Navigation -->
    <nav class="top-navbar">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="hamburger-menu me-3">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="brand-name">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40">
                        DonationAdmin
                    </a>
                </div>
                <div class="d-flex align-items-center">
                    <!-- Profile Dropdown -->
                    <div class="dropdown profile-dropdown">
                        <div class="d-flex align-items-center" data-bs-toggle="dropdown">
                            @if(Auth::guard('admin')->user()->profile_photo)
                                <img src="{{ Storage::url(Auth::guard('admin')->user()->profile_photo) }}" alt="Profile" class="me-2" style="width: 32px; height: 32px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 16px;">
                                    {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                    <i class="fas fa-user"></i>
                                    Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    @stack('scripts')
</body>
</html> 