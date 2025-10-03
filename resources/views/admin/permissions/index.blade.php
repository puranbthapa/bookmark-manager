@extends('layouts.app')

@section('title', 'Permission Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Permission Management</h1>
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Permission
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

            <!-- Permissions by Category -->
            @forelse($permissions as $category => $categoryPermissions)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-key me-2 text-primary"></i>
                            {{ $category }}
                            <span class="badge bg-secondary ms-2">{{ $categoryPermissions->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($categoryPermissions as $permission)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">{{ str_replace('.', ' ', ucfirst($permission->name)) }}</h6>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('admin.permissions.edit', $permission) }}">
                                                        <i class="fas fa-edit me-2"></i>Edit Permission
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}"
                                                              style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this permission?')">
                                                                <i class="fas fa-trash me-2"></i>Delete Permission
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="text-muted small mb-2">
                                            <strong>System Name:</strong> <code>{{ $permission->name }}</code>
                                        </div>

                                        <!-- Roles using this permission -->
                                        <div class="mb-2">
                                            <small class="text-muted">Used by roles:</small><br>
                                            @forelse($permission->roles as $role)
                                                <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'moderator' ? 'warning' : 'secondary') }} me-1">
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @empty
                                                <span class="badge bg-light text-dark">No roles</span>
                                            @endforelse
                                        </div>

                                        <!-- Users with direct permission -->
                                        @if($permission->users->count() > 0)
                                            <div>
                                                <small class="text-muted">Direct users:</small>
                                                <span class="badge bg-info">{{ $permission->users->count() }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-key fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No permissions found</h5>
                        <p class="text-muted">Create your first permission to get started with access control.</p>
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Permission
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
