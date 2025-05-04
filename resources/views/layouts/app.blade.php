<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - DonationAdmin</title>
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 0;
            margin: 0;
            overflow-x: hidden;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .top-navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
            min-height: 120px;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
            padding-top: 1rem;
        }

        .sidebar {
            position: fixed;
            top: 120px;
            bottom: 0;
            left: 0;
            width: 250px;
            background: white;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            z-index: 100;
            transition: all 0.3s ease-in-out;
        }

        .sidebar.collapsed {
            margin-left: -250px;
        }

        .main-content.expanded {
            margin-left: 0;
            width: 100%;
        }

        .sidebar-sticky {
            position: sticky;
            top: 0;
            height: calc(100vh - 70px);
            padding: 1.5rem 0;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            margin-top: 120px;
            padding: 2rem;
            min-width: 0;
            width: calc(100% - 250px);
            transition: all 0.3s ease-in-out;
        }

        .nav-link {
            color: #333;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: #007bff;
            background: #f8f9fa;
        }

        .nav-link.active {
            color: #007bff;
            background-color: #f8f9fa;
            border-right: 3px solid #007bff;
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 24px;
        }

        .sidebar-toggle {
            cursor: pointer;
            padding: 0.5rem;
            margin-right: 1rem;
            border: none;
            background: none;
            color: #333;
            transition: color 0.2s ease;
        }

        .sidebar-toggle:hover {
            color: #007bff;
        }

        .sidebar-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            cursor: pointer;
            padding: 0.5rem;
            border: none;
            background: none;
            color: #333;
            transition: color 0.2s ease;
            display: none;
        }

        .sidebar-close:hover {
            color: #dc3545;
        }

        .profile-dropdown {
            cursor: pointer;
        }

        .profile-dropdown img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: .75rem 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dropdown-item i {
            font-size: 1.1rem;
            width: 24px;
        }

        .brand-name {
            font-weight: 600;
            font-size: 1.4rem;
            color: #007bff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-name:hover {
            color: #0056b3;
            text-decoration: none;
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            .sidebar-close {
                display: block;
            }
        }

        /* Custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Container padding adjustments */
        .container-fluid {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        /* Table adjustments */
        .table {
            margin-bottom: 0;
        }
        .table td, .table th {
            padding: 1rem;
            white-space: nowrap;
        }
        .table-responsive {
            margin: 0;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .page-title {
            color: #333;
            font-weight: 500;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding-bottom: 1rem;
        }

        .page-title h1 {
            margin: 0;
            line-height: 1.2;
        }

        @media (max-width: 767.98px) {
            .page-title {
                display: none;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg top-navbar">
            <div class="container-fluid">
                <div class="d-flex flex-column w-100">
                    <h1 class="display-6 fw-bold mb-3" style="color: #2D3748;">@yield('title')</h1>
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="sidebar-toggle" id="sidebarToggle">
                            <i class="fas fa-bars" id="sidebarIcon"></i>
                        </button>
                        <a class="brand-name" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-heart text-primary"></i>
                            DonationAdmin
                        </a>
                        <div class="d-flex align-items-center">
                            <div class="dropdown profile-dropdown">
                                <div class="d-flex align-items-center" data-bs-toggle="dropdown">
                                    @if(auth('admin')->user()->profile_photo)
                                        <img src="{{ Storage::url(auth('admin')->user()->profile_photo) }}" alt="Profile" class="me-2">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                            {{ substr(auth('admin')->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="d-none d-md-block">{{ auth('admin')->user()->name }}</span>
                                    <i class="fas fa-chevron-down ms-2"></i>
                                </div>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.index') }}">
                                            <i class="fas fa-users-cog"></i> Manage Admins
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}" 
                           href="{{ route('admin.donations.index') }}">
                            <i class="fas fa-hand-holding-heart"></i>
                            Donations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.campaigns.*') ? 'active' : '' }}" 
                           href="{{ route('admin.campaigns.list') }}">
                            <i class="fas fa-bullhorn"></i>
                            Campaigns
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}" 
                           href="{{ route('admin.calendar.index') }}">
                            <i class="fas fa-calendar"></i>
                            Calendar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" 
                           href="{{ route('admin.reports.index') }}">
                            <i class="fas fa-chart-bar"></i>
                            Reports
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-sign-out-alt fa-3x text-warning mb-3"></i>
                    <h4>Are you sure you want to logout?</h4>
                    <p class="text-muted">You will be returned to the login screen</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarIcon = document.getElementById('sidebarIcon');

            function updateToggleIcon(isCollapsed) {
                sidebarIcon.classList.remove('fa-bars', 'fa-times');
                sidebarIcon.classList.add(isCollapsed ? 'fa-bars' : 'fa-times');
            }

            function toggleSidebar() {
                const willCollapse = !sidebar.classList.contains('collapsed');
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                updateToggleIcon(willCollapse);
            }

            sidebarToggle.addEventListener('click', toggleSidebar);

            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInside = sidebar.contains(event.target) || sidebarToggle.contains(event.target);
                if (!isClickInside && window.innerWidth <= 991.98 && !sidebar.classList.contains('collapsed')) {
                    toggleSidebar();
                }
            });

            // Handle initial state on mobile
            if (window.innerWidth <= 991.98) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                updateToggleIcon(true);
            }
        });
    </script>
</body>
</html> 