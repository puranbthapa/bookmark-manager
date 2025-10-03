@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">User Details</h1>
                <div>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Users
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- User Information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                <span class="text-white h2 mb-0">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-3">{{ $user->email }}</p>

                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <h4 class="text-primary mb-0">{{ $user->bookmarks->count() }}</h4>
                                    <small class="text-muted">Bookmarks</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-success mb-0">{{ $user->categories->count() }}</h4>
                                    <small class="text-muted">Categories</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-info mb-0">{{ $user->bookmarks->where('favorite', true)->count() }}</h4>
                                    <small class="text-muted">Favorites</small>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center mb-3">
                                @if($user->email_verified_at)
                                    <span class="badge bg-success fs-6">Active</span>
                                @else
                                    <span class="badge bg-secondary fs-6">Inactive</span>
                                @endif
                            </div>

                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Joined {{ $user->created_at->format('M d, Y') }}
                            </small>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-edit me-2"></i>Edit User
                                </a>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-{{ $user->email_verified_at ? 'warning' : 'success' }} btn-sm w-100">
                                            <i class="fas fa-{{ $user->email_verified_at ? 'ban' : 'check' }} me-2"></i>
                                            {{ $user->email_verified_at ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles and Permissions -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Roles & Permissions</h6>
                        </div>
                        <div class="card-body">
                            <!-- Roles -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Assigned Roles</h6>
                                @forelse($user->roles as $role)
                                    <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'moderator' ? 'warning' : 'secondary') }} me-2 mb-2">
                                        <i class="fas fa-{{ $role->name === 'admin' ? 'crown' : ($role->name === 'moderator' ? 'shield' : 'user') }} me-1"></i>
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @empty
                                    <span class="badge bg-light text-dark">No roles assigned</span>
                                @endforelse
                            </div>

                            <!-- Permissions -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">All Permissions</h6>
                                @php
                                    $allPermissions = $user->getAllPermissions()->groupBy('category');
                                @endphp

                                @forelse($allPermissions as $category => $permissions)
                                    <div class="mb-3">
                                        <h6 class="text-primary">{{ $category }}</h6>
                                        <div class="row">
                                            @foreach($permissions as $permission)
                                                <div class="col-md-6 mb-1">
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-check-circle text-success me-1"></i>
                                                        {{ str_replace('.', ' ', ucfirst($permission->name)) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">No permissions assigned</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity / Bookmarks -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Recent Bookmarks</h6>
                        </div>
                        <div class="card-body">
                            @forelse($user->bookmarks->take(5) as $bookmark)
                                <div class="d-flex align-items-center mb-2">
                                    <img src="https://www.google.com/s2/favicons?domain={{ parse_url($bookmark->url, PHP_URL_HOST) }}"
                                         alt="Favicon" class="favicon me-2">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $bookmark->title }}</h6>
                                        <small class="text-muted">{{ $bookmark->url }}</small>
                                    </div>
                                    <small class="text-muted">{{ $bookmark->created_at->diffForHumans() }}</small>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No bookmarks yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
}
.favicon {
    width: 16px;
    height: 16px;
}
</style>
@endsection
