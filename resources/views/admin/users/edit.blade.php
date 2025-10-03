@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit User: {{ $user->name }}</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <h6 class="text-muted mb-3">Basic Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password <small class="text-muted">(Leave blank to keep current)</small></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control"
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Account Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ $user->email_verified_at ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ !$user->email_verified_at ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Current Status -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-muted mb-3">Current Information</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <h5 class="text-primary">{{ $user->bookmarks->count() }}</h5>
                                            <small class="text-muted">Bookmarks</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <h5 class="text-success">{{ $user->categories->count() }}</h5>
                                            <small class="text-muted">Categories</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <h5 class="text-info">{{ $user->created_at->diffForHumans() }}</h5>
                                            <small class="text-muted">Member Since</small>
                                        </div>
                                    </div>
                                </div>
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
                                               {{ $user->hasRole($role->name) || in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
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
                                                       {{ $user->hasPermissionTo($permission->name) || in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ str_replace('.', ' ', ucfirst($permission->name)) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between">
                            <div>
                                @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-danger"
                                            onclick="if(confirm('Are you sure you want to delete this user?')) { document.getElementById('delete-form').submit(); }">
                                        <i class="fas fa-trash me-2"></i>Delete User
                                    </button>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update User
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($user->id !== auth()->id())
                        <form id="delete-form" method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
