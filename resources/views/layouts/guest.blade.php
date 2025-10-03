<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Login') - {{ config('app.name', 'Bookmark Manager') }}</title>

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            :root {
                --primary-color: #0d6efd;
                --primary-dark: #0b5ed7;
                --secondary-color: #6c757d;
                --success-color: #198754;
                --warning-color: #ffc107;
                --danger-color: #dc3545;
                --info-color: #0dcaf0;
                --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                --border-radius: 12px;
            }

            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                background: var(--gradient-bg);
                background-attachment: fixed;
                min-height: 100vh;
                position: relative;
            }

            body::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="50" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                pointer-events: none;
            }

            .auth-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
                position: relative;
                z-index: 1;
            }

            .auth-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: var(--border-radius);
                box-shadow: var(--card-shadow);
                width: 100%;
                max-width: 420px;
                overflow: hidden;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .auth-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            }

            .auth-header {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                padding: 2.5rem 2rem 2rem;
                text-align: center;
                position: relative;
            }

            .auth-header::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 0;
                height: 0;
                border-left: 15px solid transparent;
                border-right: 15px solid transparent;
                border-top: 10px solid var(--primary-dark);
            }

            .brand-logo {
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.15);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                font-size: 1.8rem;
                backdrop-filter: blur(10px);
            }

            .auth-title {
                font-size: 1.5rem;
                font-weight: 600;
                margin: 0 0 0.5rem;
            }

            .auth-subtitle {
                opacity: 0.9;
                font-size: 0.9rem;
                font-weight: 300;
            }

            .auth-body {
                padding: 2.5rem 2rem 2rem;
            }

            .form-floating {
                margin-bottom: 1.5rem;
            }

            .form-floating > .form-control {
                border: 2px solid #e9ecef;
                border-radius: 8px;
                padding: 1rem 0.75rem;
                font-size: 1rem;
                transition: all 0.3s ease;
                background: rgba(255, 255, 255, 0.8);
            }

            .form-floating > .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
                background: white;
            }

            .form-floating > label {
                color: var(--secondary-color);
                font-weight: 500;
            }

            .btn-login {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                border: none;
                border-radius: 8px;
                padding: 0.75rem 2rem;
                font-weight: 600;
                font-size: 1rem;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-login::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s ease;
            }

            .btn-login:hover::before {
                left: 100%;
            }

            .btn-login:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
            }

            .demo-credentials {
                background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                border-radius: 8px;
                padding: 1.25rem;
                margin-top: 1.5rem;
                border-left: 4px solid var(--info-color);
            }

            .demo-credentials h6 {
                color: var(--info-color);
                font-weight: 600;
                margin-bottom: 0.75rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .demo-user {
                background: white;
                border-radius: 6px;
                padding: 0.75rem 1rem;
                margin-bottom: 0.5rem;
                display: flex;
                justify-content: between;
                align-items: center;
                border: 1px solid rgba(0, 0, 0, 0.05);
                transition: all 0.2s ease;
                cursor: pointer;
            }

            .demo-user:hover {
                background: #f8f9fa;
                border-color: var(--primary-color);
                transform: translateX(5px);
            }

            .demo-user:last-child {
                margin-bottom: 0;
            }

            .demo-user .user-info {
                flex: 1;
            }

            .demo-user .user-email {
                font-weight: 500;
                color: #495057;
                font-size: 0.9rem;
            }

            .demo-user .user-role {
                font-size: 0.75rem;
                color: var(--secondary-color);
            }

            .demo-user .copy-btn {
                background: var(--primary-color);
                color: white;
                border: none;
                border-radius: 4px;
                padding: 0.25rem 0.5rem;
                font-size: 0.7rem;
                opacity: 0;
                transition: opacity 0.2s ease;
            }

            .demo-user:hover .copy-btn {
                opacity: 1;
            }

            .form-check {
                margin: 1.5rem 0;
            }

            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .forgot-password {
                color: var(--primary-color);
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
            }

            .forgot-password:hover {
                color: var(--primary-dark);
                text-decoration: underline;
            }

            .alert {
                border: none;
                border-radius: 8px;
                margin-bottom: 1.5rem;
            }

            .invalid-feedback {
                font-size: 0.85rem;
                margin-top: 0.25rem;
            }

            .theme-toggle {
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: rgba(255, 255, 255, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.3);
                color: white;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
                backdrop-filter: blur(10px);
            }

            .theme-toggle:hover {
                background: rgba(255, 255, 255, 0.3);
                transform: scale(1.05);
            }

            /* Dark theme support */
            [data-bs-theme="dark"] {
                --gradient-bg: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            }

            [data-bs-theme="dark"] .auth-card {
                background: rgba(33, 37, 41, 0.95);
                border-color: rgba(255, 255, 255, 0.1);
            }

            [data-bs-theme="dark"] .form-floating > .form-control {
                background: rgba(255, 255, 255, 0.05);
                border-color: rgba(255, 255, 255, 0.1);
                color: white;
            }

            [data-bs-theme="dark"] .form-floating > .form-control:focus {
                background: rgba(255, 255, 255, 0.1);
            }

            [data-bs-theme="dark"] .demo-credentials {
                background: rgba(255, 255, 255, 0.05);
            }

            [data-bs-theme="dark"] .demo-user {
                background: rgba(255, 255, 255, 0.05);
                border-color: rgba(255, 255, 255, 0.1);
            }

            [data-bs-theme="dark"] .demo-user:hover {
                background: rgba(255, 255, 255, 0.1);
            }

            /* Animations */
            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .auth-card {
                animation: slideInUp 0.6s ease-out;
            }

            /* Mobile responsiveness */
            @media (max-width: 576px) {
                .auth-container {
                    padding: 1rem;
                }

                .auth-header {
                    padding: 2rem 1.5rem 1.5rem;
                }

                .auth-body {
                    padding: 2rem 1.5rem;
                }

                .brand-logo {
                    width: 50px;
                    height: 50px;
                    font-size: 1.5rem;
                }
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="auth-container">
            <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
                <i class="bi bi-moon" id="theme-icon"></i>
            </button>

            <div class="auth-card">
                <div class="auth-header">
                    <div class="brand-logo">
                        <i class="bi bi-bookmarks"></i>
                    </div>
                    <h1 class="auth-title">@yield('auth-title', 'Welcome Back')</h1>
                    <p class="auth-subtitle">@yield('auth-subtitle', 'Sign in to access your bookmarks')</p>
                </div>

                <div class="auth-body">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            // Theme toggle functionality
            function toggleTheme() {
                const html = document.documentElement;
                const themeIcon = document.getElementById('theme-icon');

                if (html.getAttribute('data-bs-theme') === 'dark') {
                    html.setAttribute('data-bs-theme', 'light');
                    themeIcon.className = 'bi bi-moon';
                    localStorage.setItem('theme', 'light');
                } else {
                    html.setAttribute('data-bs-theme', 'dark');
                    themeIcon.className = 'bi bi-sun';
                    localStorage.setItem('theme', 'dark');
                }
            }

            // Load saved theme
            document.addEventListener('DOMContentLoaded', function() {
                const savedTheme = localStorage.getItem('theme') || 'light';
                const html = document.documentElement;
                const themeIcon = document.getElementById('theme-icon');

                html.setAttribute('data-bs-theme', savedTheme);
                themeIcon.className = savedTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
            });

            // Demo credential functionality
            function fillCredentials(email, password) {
                document.getElementById('email').value = email;
                document.getElementById('password').value = password;

                // Trigger Bootstrap floating label
                document.getElementById('email').focus();
                document.getElementById('password').focus();
                document.activeElement.blur();
            }

            // Copy to clipboard functionality
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    // Could add a toast notification here
                });
            }
        </script>
    </body>
</html>
