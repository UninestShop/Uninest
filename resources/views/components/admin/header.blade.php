<header class="admin-header bg-white shadow-sm">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center py-3">
            <!-- Left side: Toggle button and breadcrumb -->
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-dark d-lg-none me-2" id="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        @if(isset($title))
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        @endif
                    </ol>
                </nav>
            </div>
            
            <!-- Right side: Notifications, profile -->
            <div class="d-flex align-items-center">
                <!-- Notifications dropdown -->
                {{-- <div class="dropdown me-3">
                    <button class="btn btn-link text-dark position-relative" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationsDropdown">
                        <li><h6 class="dropdown-header">Notifications</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-plus text-info"></i>
                                </div>
                                <div class="ms-2">
                                    <p class="mb-0">New user registered</p>
                                    <small class="text-muted">5 minutes ago</small>
                                </div>
                            </div>
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-flag text-warning"></i>
                                </div>
                                <div class="ms-2">
                                    <p class="mb-0">Product reported</p>
                                    <small class="text-muted">1 hour ago</small>
                                </div>
                            </div>
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-shopping-cart text-success"></i>
                                </div>
                                <div class="ms-2">
                                    <p class="mb-0">New transaction</p>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
                    </ul>
                </div> --}}
                
                <!-- User profile dropdown -->
                <div class="dropdown">
                    <button class="btn btn-link text-dark d-flex align-items-center" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        @php
                            $user = Auth::guard('admin')->user();
                            $userName = $user ? $user->name : 'Admin User';
                            $firstLetter = substr($userName, 0, 1);
                        @endphp
                        
                        @if(Auth::user()->image)
                            <img src="{{ asset(Auth::user()->image) }}" class="rounded-circle me-2" alt="Profile Image" width="36" height="36">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                <span class="text-white" style="font-size: 16px;">{{ $firstLetter }}</span>
                            </div>
                        @endif
                        <span class="d-none d-md-inline">{{ $userName }}</span>
                        <i class="fas fa-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                            <i class="fas fa-user me-2"></i> My Profile
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
