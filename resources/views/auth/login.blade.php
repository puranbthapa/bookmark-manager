@extends('layouts.guest')

@section('title', 'Login')
@section('auth-title', 'Welcome Back')
@section('auth-subtitle', 'Sign in to access your bookmark collection')

@section('content')
<!-- Session Status -->
@if (session('status'))
    <div class="alert alert-success" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf

    <!-- Email Address -->
    <div class="form-floating">
        <input type="email"
               class="form-control @error('email') is-invalid @enderror"
               id="email"
               name="email"
               value="{{ old('email') }}"
               placeholder="name@example.com"
               required
               autofocus
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
               autocomplete="current-password">
        <label for="password">
            <i class="bi bi-lock me-2"></i>Password
        </label>
        @error('password')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Remember Me & Forgot Password -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me">
                <i class="bi bi-check-square me-1"></i>
                Remember me
            </label>
        </div>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-password">
                <i class="bi bi-question-circle me-1"></i>
                Forgot password?
            </a>
        @endif
    </div>

    <!-- Login Button -->
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>
            Sign In
        </button>
    </div>

    <!-- Registration Link -->
    @if (Route::has('register'))
        <div class="text-center">
            <span class="text-muted">Don't have an account?</span>
            <a href="{{ route('register') }}" class="forgot-password ms-1">
                <i class="bi bi-person-plus me-1"></i>
                Create one here
            </a>
        </div>
    @endif
</form>

<!-- Demo Credentials -->
<div class="demo-credentials">
    <h6>
        <i class="bi bi-info-circle"></i>
        Demo Accounts
    </h6>
    <div class="demo-user" onclick="fillCredentials('john@example.com', 'password')">
        <div class="user-info">
            <div class="user-email">john@example.com</div>
            <div class="user-role">Regular User • 25+ bookmarks</div>
        </div>
        <button type="button" class="copy-btn" onclick="event.stopPropagation(); copyToClipboard('john@example.com')">
            Copy
        </button>
    </div>
    <div class="demo-user" onclick="fillCredentials('jane@example.com', 'password')">
        <div class="user-info">
            <div class="user-email">jane@example.com</div>
            <div class="user-role">Regular User • 18+ bookmarks</div>
        </div>
        <button type="button" class="copy-btn" onclick="event.stopPropagation(); copyToClipboard('jane@example.com')">
            Copy
        </button>
    </div>
    <div class="demo-user" onclick="fillCredentials('admin@bookmarks.com', 'password')">
        <div class="user-info">
            <div class="user-email">admin@bookmarks.com</div>
            <div class="user-role">Administrator • Full Access</div>
        </div>
        <button type="button" class="copy-btn" onclick="event.stopPropagation(); copyToClipboard('admin@bookmarks.com')">
            Copy
        </button>
    </div>
    <div class="mt-2">
        <small class="text-muted">
            <i class="bi bi-shield-check me-1"></i>
            All demo accounts use password: <code>password</code>
        </small>
    </div>
</div>
@endsection
