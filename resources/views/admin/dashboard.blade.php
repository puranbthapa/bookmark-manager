@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                    Admin Dashboard
                </h1>
                <div class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    {{ now()->format('M d, Y - H:i') }}
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $totalUsers }}</h4>
                                    <p class="mb-0">Total Users</p>
                                </div>
                                <i class="fas fa-users fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $totalBookmarks }}</h4>
                                    <p class="mb-0">Total Bookmarks</p>
                                </div>
                                <i class="fas fa-bookmark fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $totalCategories }}</h4>
                                    <p class="mb-0">Total Categories</p>
                                </div>
                                <i class="fas fa-folder fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $totalTags }}</h4>
                                    <p class="mb-0">Total Tags</p>
                                </div>
                                <i class="fas fa-tags fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Users -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Users</h5>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body">
                            @forelse($recentUsers as $user)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <span class="text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                    <div class="text-end">
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'moderator' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                        <div class="small text-muted">{{ $user->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No users found</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">System Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <h4 class="text-danger">{{ $adminCount }}</h4>
                                    <small class="text-muted">Administrators</small>
                                </div>
                                <div class="col-6 mb-3">
                                    <h4 class="text-warning">{{ $moderatorCount }}</h4>
                                    <small class="text-muted">Moderators</small>
                                </div>
                                <div class="col-6 mb-3">
                                    <h4 class="text-success">{{ $activeUsers }}</h4>
                                    <small class="text-muted">Active Users</small>
                                </div>
                                <div class="col-6 mb-3">
                                    <h4 class="text-secondary">{{ $inactiveUsers }}</h4>
                                    <small class="text-muted">Inactive Users</small>
                                </div>
                            </div>

                            <hr>

                            <div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Laravel Version:</span>
                                    <strong>{{ app()->version() }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>PHP Version:</span>
                                    <strong>{{ PHP_VERSION }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Environment:</span>
                                    <strong>{{ app()->environment() }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-user-plus me-2"></i>Add New User
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('admin.roles.create') }}" class="btn btn-outline-success w-100">
                                        <i class="fas fa-shield-alt me-2"></i>Create Role
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-outline-info w-100">
                                        <i class="fas fa-key me-2"></i>Add Permission
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-warning w-100">
                                        <i class="fas fa-users-cog me-2"></i>Manage Users
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
}
</style>
@endsection
