@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Role Management</h1>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Role
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

            <!-- Roles Cards -->
            <div class="row">
                @forelse($roles as $role)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'moderator' ? 'warning' : 'secondary') }} me-2">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                </h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.roles.show', $role) }}">
                                            <i class="fas fa-eye me-2"></i>View Details
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.roles.edit', $role) }}">
                                            <i class="fas fa-edit me-2"></i>Edit Role
                                        </a></li>
                                        @if($role->name !== 'admin')
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this role?')">
                                                        <i class="fas fa-trash me-2"></i>Delete Role
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="border-end">
                                            <h4 class="mb-0 text-primary">{{ $role->users_count }}</h4>
                                            <small class="text-muted">Users</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="mb-0 text-success">{{ $role->permissions->count() }}</h4>
                                        <small class="text-muted">Permissions</small>
                                    </div>
                                </div>

                                <!-- Sample Permissions -->
                                @if($role->permissions->count() > 0)
                                    <h6 class="text-muted mb-2">Key Permissions:</h6>
                                    <div class="permission-list">
                                        @foreach($role->permissions->take(4) as $permission)
                                            <span class="badge bg-light text-dark me-1 mb-1">
                                                {{ str_replace(['.', '_'], [' ', ' '], ucfirst($permission->name)) }}
                                            </span>
                                        @endforeach
                                        @if($role->permissions->count() > 4)
                                            <span class="badge bg-secondary">+{{ $role->permissions->count() - 4 }} more</span>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-muted mb-0">No permissions assigned</p>
                                @endif
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        @if($role->name === 'admin')
                                            <i class="fas fa-crown text-warning me-1"></i>System Administrator
                                        @elseif($role->name === 'moderator')
                                            <i class="fas fa-shield text-info me-1"></i>Content Moderator
                                        @else
                                            <i class="fas fa-user text-secondary me-1"></i>Standard Role
                                        @endif
                                    </small>
                                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-shield-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No roles found</h5>
                                <p class="text-muted">Create your first role to get started with permission management.</p>
                                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create Role
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
.permission-list {
    max-height: 80px;
    overflow-y: auto;
}
</style>
@endsection
