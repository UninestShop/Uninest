<div class="sidebar bg-dark text-white" style="min-height: 100vh; width: 250px;">
    <div class="p-3">
        <h5 class="sidebar-heading text-uppercase">Admin Dashboard</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>

            <!-- User Management Section -->
            <li class="nav-item">
                <a href="#userSubmenu" data-bs-toggle="collapse" class="nav-link text-white">
                    <i class="fas fa-users"></i> User Management <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="collapse list-unstyled" id="userSubmenu">
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="nav-link text-white ps-4">
                            <i class="fas fa-user-friends"></i> Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.roles.index') }}" class="nav-link text-white ps-4">
                            <i class="fas fa-user-tag"></i> Roles
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.permissions.index') }}" class="nav-link text-white ps-4">
                            <i class="fas fa-key"></i> Permissions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.permissions') }}" class="nav-link text-white ps-4">
                            <i class="fas fa-user-lock"></i> User Permissions
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Products Section -->
            <li class="nav-item">
                <a href="#productSubmenu" data-bs-toggle="collapse" class="nav-link text-white">
                    <i class="fas fa-box"></i> Product Management <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="collapse list-unstyled" id="productSubmenu">
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="nav-link text-white ps-4">
                            <i class="fas fa-list"></i> All Products
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.create') }}" class="nav-link text-white ps-4">
                            <i class="fas fa-plus"></i> Add Product
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Transaction Section -->
            <li class="nav-item">
                <a href="#transactionSubmenu" data-bs-toggle="collapse" class="nav-link text-white">
                    <i class="fas fa-exchange-alt"></i> Transactions <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="collapse list-unstyled" id="transactionSubmenu">
                    <li>
                        <a href="{{ route('admin.transactions.index') }}" class="nav-link text-white ps-4">
                            <i class="fas fa-list"></i> All Transactions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.transactions.issues') }}" class="nav-link text-white ps-4">
                            <i class="fas fa-exclamation-triangle"></i> Issues
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Messages Section -->
            <li class="nav-item">
                <a href="#messageSubmenu" data-bs-toggle="collapse" class="nav-link text-white">
                    <i class="fas fa-envelope"></i> Messages <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="collapse list-unstyled" id="messageSubmenu">
                    <li>
                        <a href="{{ route('admin.messages.index') }}" class="nav-link text-white ps-4">
                            <i class="fas fa-inbox"></i> Inbox
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.messages.history') }}" class="nav-link text-white ps-4">
                            <i class="fas fa-history"></i> History
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Logout Link -->
            <li class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link text-white">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
