<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Admin</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" sizes="16x16 32x32" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }
        .nav-link {
            color: rgba(255,255,255,.8);
        }
        .nav-link:hover {
            color: #fff;
        }
        .nav-link.active {
            background: rgba(255,255,255,.1);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container-fluid p-0 flex-grow-1">
        <div class="row g-0 flex-nowrap min-vh-100">
            <!-- Sidebar -->
            <div class="sidebar col-auto px-0" id="sidebar">
                <div class="p-3">
                    <h5 class="text-white">Admin Panel</h5>
                    <hr class="text-white">
                    <ul class="nav flex-column mb-auto">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link">
                                <i class="fas fa-users me-2"></i>Users
                            </a>
                        </li>
                        
                        
                        <!-- User Management Dropdown -->
                       
                        
                        <!-- Product Management -->
                        <li class="nav-item">
                            <a href="{{ route('admin.products.index') }}" class="nav-link">
                                <i class="fas fa-box me-2"></i>Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}" class="nav-link">
                                <i class="fas fa-list me-2"></i>Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.universities.index') }}" class="nav-link">
                                <i class="fas fa-university"></i> Universities
                            </a>
                        </li>
                 
                        <li class="nav-item">
                            <a href="{{ route('admin.messages.index') }}" class="nav-link">
                                <i class="fas fa-envelope me-2"></i>Messages Monitoring
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cms.index') }}" class="nav-link">
                                <i class="fas fa-file-alt me-2"></i>CMS Pages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.products.report')}}" class="nav-link">
                                <i class="fas fa-exclamation-triangle me-2"></i>Product Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.products.inquiry')}}" class="nav-link">
                                <i class="fas fa-info-circle me-2"></i>Inquiries
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col p-0 d-flex flex-column">
                <!-- Include the admin header component -->
                <x-admin.header :title="$title ?? ''" />
                
                <div class="p-3 flex-grow-1">
                    @yield('content')
                </div>
                
                <!-- Include the admin footer component -->
                <x-admin.footer 
                    :userCount="App\Models\User::count()"
                    :productCount="App\Models\Product::count()"
                    :transactionCount="App\Models\Transaction::count()" 
                />
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            
            if (sidebar.classList.contains('show') &&
                !sidebar.contains(event.target) &&
                !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
        
        // Highlight active menu item
        const currentUrl = window.location.href;
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add('active');
                const dropdown = link.closest('.collapse');
                if (dropdown) {
                    dropdown.classList.add('show');
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
