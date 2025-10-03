@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">User Management</h1>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New User
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.users.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search"
                                       placeholder="Search users..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="role">
                                    <option value="">All Roles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}"
                                                {{ request('role') === $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Status</th>
                                    <th>Bookmarks</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <span class="text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    <small class="text-muted">#{{ $user->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'moderator' ? 'warning' : 'secondary') }} me-1">
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @endforeach
                                            @if($user->roles->isEmpty())
                                                <span class="badge bg-light text-dark">No Role</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info rounded-pill">{{ $user->bookmarks_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 align-items-center" style="min-width: 200px;">
                                                <!-- View Button -->
                                                <a href="{{ route('admin.users.show', $user) }}"
                                                   class="btn btn-sm btn-outline-info d-flex align-items-center px-3"
                                                   data-bs-toggle="tooltip"
                                                   title="View Details">
                                                    <i class="bi bi-eye me-1"></i>
                                                    <span class="fw-medium">View</span>
                                                </a>

                                                <!-- Edit Button -->
                                                <a href="{{ route('admin.users.edit', $user) }}"
                                                   class="btn btn-sm btn-outline-primary d-flex align-items-center px-3"
                                                   data-bs-toggle="tooltip"
                                                   title="Edit User">
                                                    <i class="bi bi-pencil me-1"></i>
                                                    <span class="fw-medium">Edit</span>
                                                </a>

                                                @if($user->id !== auth()->id())
                                                    <!-- Status Toggle -->
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-{{ $user->email_verified_at ? 'warning' : 'success' }} d-flex align-items-center px-3 toggle-status-btn"
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-name="{{ $user->name }}"
                                                            data-current-status="{{ $user->email_verified_at ? 'active' : 'inactive' }}"
                                                            data-bs-toggle="tooltip"
                                                            title="{{ $user->email_verified_at ? 'Deactivate User' : 'Activate User' }}">
                                                        <i class="bi bi-{{ $user->email_verified_at ? 'x-circle' : 'check-circle' }} me-1"></i>
                                                        <span class="fw-medium">{{ $user->email_verified_at ? 'Disable' : 'Enable' }}</span>
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger d-flex align-items-center px-3 delete-user-btn"
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-name="{{ $user->name }}"
                                                            data-user-email="{{ $user->email }}"
                                                            data-bs-toggle="tooltip"
                                                            title="Delete Permanently">
                                                        <i class="bi bi-trash me-1"></i>
                                                        <span class="fw-medium">Delete</span>
                                                    </button>
                                                @else
                                                    <!-- Self Protection -->
                                                    <button class="btn btn-sm btn-outline-secondary d-flex align-items-center px-3"
                                                            disabled
                                                            data-bs-toggle="tooltip"
                                                            title="Cannot modify your own account">
                                                        <i class="bi bi-shield-lock me-1"></i>
                                                        <span class="fw-medium">Protected</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-3x mb-3"></i>
                                                <p>No users found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}

/* Professional Action Button Styling */
.btn-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    border-width: 1.5px;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
    min-height: 32px;
    white-space: nowrap;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-sm:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Enhanced button colors */
.btn-outline-info {
    color: #0dcaf0;
    border-color: #0dcaf0;
}

.btn-outline-info:hover {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
}

.btn-outline-primary {
    color: #0d6efd;
    border-color: #0d6efd;
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.btn-outline-warning {
    color: #fd7e14;
    border-color: #fd7e14;
}

.btn-outline-warning:hover {
    background-color: #fd7e14;
    border-color: #fd7e14;
    color: #fff;
}

.btn-outline-success {
    color: #198754;
    border-color: #198754;
}

.btn-outline-success:hover {
    background-color: #198754;
    border-color: #198754;
    color: #fff;
}

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-secondary:hover:not(:disabled) {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

/* Icon styling */
.bi {
    font-size: 1rem;
    line-height: 1;
}

/* Disabled button styling */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Action container styling */
.d-flex.gap-2 {
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-start;
}

/* Professional polish */
.table td {
    vertical-align: middle;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
        align-items: stretch;
    }

    .btn-sm {
        width: 100%;
        justify-content: center;
    }
}

/* Smooth transitions */
.btn {
    transition: all 0.2s ease-in-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle status toggle buttons
    document.querySelectorAll('.toggle-status-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            const currentStatus = this.dataset.currentStatus;
            const action = currentStatus === 'active' ? 'deactivate' : 'activate';

            if (confirm(`Are you sure you want to ${action} ${userName}?`)) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${userId}/toggle-status`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="PATCH">
                `;

                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Handle delete buttons
    document.querySelectorAll('.delete-user-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            const userEmail = this.dataset.userEmail;

            const confirmed = confirm(`⚠️ DANGER: This will permanently delete ${userName} (${userEmail}) and all their data!\n\nThis action cannot be undone!\n\nAre you absolutely sure?`);

            if (confirmed) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${userId}`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                `;

                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Enhanced delete confirmation
    document.querySelectorAll('form[action*="destroy"] button').forEach(function(button) {
        button.addEventListener('click', function(e) {
            const userName = this.closest('tr').querySelector('h6').textContent;
            const userEmail = this.closest('tr').querySelector('td:nth-child(2)').textContent;

            const confirmMsg = `⚠️ DANGER: This action cannot be undone!

User: ${userName}
Email: ${userEmail}

This will permanently delete:
• User account
• All bookmarks
• All categories
• All associated data

Type "DELETE" to confirm:`;

            const userInput = prompt(confirmMsg);
            if (userInput !== 'DELETE') {
                e.preventDefault();
                alert('User deletion cancelled. You must type "DELETE" exactly to confirm.');
                return false;
            }
        });
    });
});
</script>
@endsection
