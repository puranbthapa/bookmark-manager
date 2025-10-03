@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Create New User</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- Basic Information -->
                        <h6 class="text-muted mb-3">Basic Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control"
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <hr>

                        <!-- Roles -->
                        <h6 class="text-muted mb-3">Roles</h6>
                        <div class="row mb-4">
                            @foreach($roles as $role)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               id="role_{{ $role->id }}" name="roles[]" value="{{ $role->name }}"
                                               {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            <strong>{{ ucfirst($role->name) }}</strong>
                                            @if($role->name === 'admin')
                                                <small class="text-danger d-block">Full system access</small>
                                            @elseif($role->name === 'moderator')
                                                <small class="text-warning d-block">Limited admin access</small>
                                            @else
                                                <small class="text-muted d-block">Standard user access</small>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <!-- Direct Permissions -->
                        <h6 class="text-muted mb-3">Additional Permissions</h6>
                        <p class="text-muted small mb-3">These permissions will be added in addition to role permissions.</p>

                        @foreach($permissions->groupBy('category') as $category => $categoryPermissions)
                            <div class="mb-3">
                                <h6 class="text-primary">{{ $category }}</h6>
                                <div class="row">
                                    @foreach($categoryPermissions as $permission)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       id="permission_{{ $permission->id }}"
                                                       name="permissions[]" value="{{ $permission->name }}"
                                                       {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ str_replace('.', ' ', ucfirst($permission->name)) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
