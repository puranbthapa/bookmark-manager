@extends('layouts.guest')

@section('title', 'Register')
@section('auth-title', 'Create Account')
@section('auth-subtitle', 'Join thousands of users organizing their bookmarks')

@section('content')
<form method="POST" action="{{ route('register') }}" id="registerForm">
    @csrf

    <!-- Name -->
    <div class="form-floating">
        <input type="text"
               class="form-control @error('name') is-invalid @enderror"
               id="name"
               name="name"
               value="{{ old('name') }}"
               placeholder="John Doe"
               required
               autofocus
               autocomplete="name">
        <label for="name">
            <i class="bi bi-person me-2"></i>Full Name
        </label>
        @error('name')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Email Address -->
    <div class="form-floating">
        <input type="email"
               class="form-control @error('email') is-invalid @enderror"
               id="email"
               name="email"
               value="{{ old('email') }}"
               placeholder="name@example.com"
               required
               autocomplete="username">
        <label for="email">
            <i class="bi bi-envelope me-2"></i>Email Address
        </label>
        @error('email')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-floating">
        <input type="password"
               class="form-control @error('password') is-invalid @enderror"
               id="password"
               name="password"
               placeholder="Password"
               required
               autocomplete="new-password">
        <label for="password">
            <i class="bi bi-lock me-2"></i>Password
        </label>
        @error('password')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ $message }}
            </div>
        @enderror
        <div class="form-text">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Password must be at least 8 characters long
            </small>
        </div>
    </div>

    <!-- Confirm Password -->
    <div class="form-floating">
        <input type="password"
               class="form-control @error('password_confirmation') is-invalid @enderror"
               id="password_confirmation"
               name="password_confirmation"
               placeholder="Confirm Password"
               required
               autocomplete="new-password">
        <label for="password_confirmation">
            <i class="bi bi-shield-check me-2"></i>Confirm Password
        </label>
        @error('password_confirmation')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Terms Agreement -->
    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" id="terms" required>
        <label class="form-check-label" for="terms">
            <i class="bi bi-check-square me-1"></i>
            I agree to the <a href="#" class="forgot-password">Terms of Service</a> and <a href="#" class="forgot-password">Privacy Policy</a>
        </label>
    </div>

    <!-- Register Button -->
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary btn-login">
            <i class="bi bi-person-plus me-2"></i>
            Create Account
        </button>
    </div>

    <!-- Login Link -->
    <div class="text-center">
        <span class="text-muted">Already have an account?</span>
        <a href="{{ route('login') }}" class="forgot-password ms-1">
            <i class="bi bi-box-arrow-in-right me-1"></i>
            Sign in here
        </a>
    </div>
</form>

<!-- Registration Benefits -->
<div class="demo-credentials">
    <h6>
        <i class="bi bi-star"></i>
        Why Join Us?
    </h6>
    <div class="d-flex flex-column gap-2">
        <div class="d-flex align-items-center">
            <i class="bi bi-bookmark-check text-success me-2"></i>
            <small>Organize unlimited bookmarks</small>
        </div>
        <div class="d-flex align-items-center">
            <i class="bi bi-tags text-primary me-2"></i>
            <small>Smart tagging and categorization</small>
        </div>
        <div class="d-flex align-items-center">
            <i class="bi bi-search text-info me-2"></i>
            <small>Powerful search across all content</small>
        </div>
        <div class="d-flex align-items-center">
            <i class="bi bi-phone text-warning me-2"></i>
            <small>Chrome extension for easy saving</small>
        </div>
        <div class="d-flex align-items-center">
            <i class="bi bi-shield-check text-success me-2"></i>
            <small>100% free and secure</small>
        </div>
    </div>
</div>
@endsection
