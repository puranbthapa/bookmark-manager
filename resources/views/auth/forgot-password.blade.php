@extends('layouts.guest')

@section('title', 'Reset Password')
@section('auth-title', 'Reset Password')
@section('auth-subtitle', 'Enter your email to receive a reset link')

@section('content')
<!-- Information Message -->
<div class="alert alert-info" role="alert">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Forgot your password?</strong> No problem! Just enter your email address and we'll send you a secure link to reset your password.
</div>

<!-- Session Status -->
@if (session('status'))
    <div class="alert alert-success" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <!-- Email Address -->
    <div class="form-floating mb-4">
        <input type="email"
               class="form-control @error('email') is-invalid @enderror"
               id="email"
               name="email"
               value="{{ old('email') }}"
               placeholder="name@example.com"
               required
               autofocus>
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

    <!-- Reset Button -->
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary btn-login">
            <i class="bi bi-arrow-clockwise me-2"></i>
            Send Reset Link
        </button>
    </div>

    <!-- Back to Login -->
    <div class="text-center">
        <a href="{{ route('login') }}" class="forgot-password">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Login
        </a>
    </div>
</form>

<!-- Help Section -->
<div class="demo-credentials">
    <h6>
        <i class="bi bi-question-circle"></i>
        Need Help?
    </h6>
    <div class="d-flex flex-column gap-2">
        <div class="d-flex align-items-start">
            <i class="bi bi-1-circle text-primary me-2 mt-1"></i>
            <small>Enter the email address associated with your account</small>
        </div>
        <div class="d-flex align-items-start">
            <i class="bi bi-2-circle text-primary me-2 mt-1"></i>
            <small>Check your email inbox (and spam folder) for the reset link</small>
        </div>
        <div class="d-flex align-items-start">
            <i class="bi bi-3-circle text-primary me-2 mt-1"></i>
            <small>Click the link and follow the instructions to create a new password</small>
        </div>
    </div>
    <div class="mt-3 pt-2 border-top">
        <small class="text-muted">
            <i class="bi bi-shield-check me-1"></i>
            This process is completely secure and your account will remain protected.
        </small>
    </div>
</div>
@endsection
