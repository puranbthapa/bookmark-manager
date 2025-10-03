<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #059669;
            --danger-color: #dc2626;
            --warning-color: #d97706;
            --info-color: #0891b2;
            --sidebar-bg: #f8fafc;
            --sidebar-border: #e2e8f0;
            --header-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #f1f5f9;
            background-image: radial-gradient(circle at 1px 1px, rgba(37, 99, 235, 0.03) 1px, transparent 0);
            background-size: 20px 20px;
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.6;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Professional Sidebar */
        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .sidebar.collapsed {
            margin-left: -280px;
        }

        .sidebar-header {
            padding: 1.75rem 1.5rem;
            border-bottom: 1px solid var(--sidebar-border);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            position: relative;
        }

        .sidebar-brand {
            font-weight: 700;
            font-size: 1.375rem;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            transition: all 0.2s ease;
        }

        .sidebar-brand:hover {
            color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .sidebar-brand i {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.125rem;
            box-shadow: var(--shadow-md);
        }

        /* Professional Navigation */
        .sidebar-nav {
            padding: 1.5rem 0;
            height: calc(100vh - 140px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--border-color) transparent;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 2px;
        }

        .nav-section {
            margin-bottom: 2.5rem;
        }

        .nav-section-title {
            font-size: 0.8125rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 0 1.5rem 0.75rem;
            margin-bottom: 0.75rem;
            position: relative;
        }

        .nav-section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1.5rem;
            right: 1.5rem;
            height: 1px;
            background: linear-gradient(90deg, var(--border-color) 0%, transparent 100%);
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border: none;
            background: none;
            margin: 0 0.75rem;
            border-radius: var(--radius-lg);
        }

        .nav-link:hover {
            background-color: rgba(37, 99, 235, 0.08);
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            font-weight: 600;
            box-shadow: var(--shadow-md);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background-color: var(--primary-color);
            border-radius: 0 2px 2px 0;
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .nav-badge {
            margin-left: auto;
            background-color: var(--secondary-color);
            color: white;
            font-size: 0.6875rem;
            padding: 0.25rem 0.625rem;
            border-radius: 12px;
            font-weight: 600;
            min-width: 24px;
            text-align: center;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .nav-link.active .nav-badge {
            background-color: rgba(255, 255, 255, 0.25);
            color: white;
        }

        .nav-link:hover .nav-badge {
            background-color: var(--primary-color);
            color: white;
        }

        /* Category Navigation */
        .category-nav .nav-link {
            padding-left: 2.5rem;
            font-size: 0.875rem;
        }

        .category-icon {
            width: 16px;
            height: 16px;
            margin-right: 0.5rem;
        }

        /* Professional Toggle Button */
        .sidebar-toggle {
            position: fixed;
            top: 1.5rem;
            left: 1.5rem;
            z-index: 1050;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1.125rem;
            backdrop-filter: blur(10px);
        }

        .sidebar-toggle:hover {
            background: linear-gradient(135deg, var(--primary-hover), #1e40af);
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.25);
        }

        .sidebar-toggle.sidebar-open {
            left: 308px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .sidebar-toggle.sidebar-open:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
        }

        /* Professional Main Content */
        .main-content {
            margin-left: 280px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            background: #f1f5f9;
        }

        .main-content.sidebar-collapsed {
            margin-left: 0;
        }

        .content-wrapper {
            animation: slideInUp 0.4s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-header {
            background: linear-gradient(135deg, var(--header-bg) 0%, #f8fafc 100%);
            border-bottom: 1px solid var(--border-color);
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .main-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .header-actions .btn {
            white-space: nowrap;
        }

        .header-actions .btn i {
            margin-right: 0.5rem;
        }

        /* Professional Cards */
        .card {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            background: white;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color), var(--success-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .card:hover::before {
            opacity: 1;
        }

        .card-header {
            background: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Professional Buttons */
        .btn {
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-lg);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.875rem;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            border: none;
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-hover), #1e40af);
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.25);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        /* Professional Bookmark Cards */
        .bookmark-card {
            transition: all 0.2s ease;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            background: white;
            padding: 1rem;
            margin-bottom: 0.75rem;
        }

        .bookmark-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
            border-color: var(--primary-color);
        }

        .bookmark-title {
            font-weight: 600;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 1rem;
        }

        .bookmark-url {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .bookmark-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            line-height: 1.5;
        }

        .favicon {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
            border-radius: var(--radius-sm);
        }

        /* Professional Badges */
        .badge {
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius-md);
            font-size: 0.75rem;
        }

        /* Theme Toggle */
        .theme-toggle {
            cursor: pointer;
            padding: 0.625rem;
            border-radius: var(--radius-lg);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(37, 99, 235, 0.08);
            border: 1px solid rgba(37, 99, 235, 0.2);
            color: var(--primary-color);
            font-size: 0.875rem;
        }

        .theme-toggle:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.05);
            box-shadow: var(--shadow-md);
        }

        /* Dark Theme */
        [data-bs-theme="dark"] {
            --sidebar-bg: #1e293b;
            --sidebar-border: #334155;
            --header-bg: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: #334155;
        }

        [data-bs-theme="dark"] body {
            background-color: #0f172a;
            color: var(--text-primary);
        }

        [data-bs-theme="dark"] .sidebar {
            background: var(--sidebar-bg);
            border-right-color: var(--sidebar-border);
        }

        [data-bs-theme="dark"] .main-content {
            background: #0f172a;
        }

        [data-bs-theme="dark"] .main-header {
            background: var(--header-bg);
            border-bottom-color: var(--border-color);
        }

        [data-bs-theme="dark"] .card {
            background: #1e293b;
            border-color: var(--border-color);
        }

        [data-bs-theme="dark"] .card-header {
            background: #334155;
            border-bottom-color: var(--border-color);
        }

        [data-bs-theme="dark"] .bookmark-card {
            background: #1e293b;
            border-color: var(--border-color);
        }

        [data-bs-theme="dark"] .nav-link:hover {
            background-color: #334155;
        }

        [data-bs-theme="dark"] .nav-link.active {
            background-color: #1e40af;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                width: 260px;
            }

            .main-content {
                margin-left: 260px;
            }

            .sidebar-toggle.sidebar-open {
                left: 280px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
                box-shadow: var(--shadow-lg);
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0 !important;
            }

            .sidebar-toggle {
                left: 1.25rem;
                top: 1.25rem;
                width: 44px;
                height: 44px;
            }

            .sidebar-toggle.sidebar-open {
                left: 1.25rem;
            }

            .main-header {
                padding: 1.5rem 1.75rem;
            }

            .main-header h1 {
                font-size: 1.75rem;
            }

            .header-actions {
                margin-top: 1rem;
                justify-content: flex-start;
            }

            .nav-link {
                padding: 1rem 1.5rem;
                font-size: 0.9375rem;
            }

            .sidebar-header {
                padding: 1.5rem 1.25rem;
            }
        }        /* Professional Table */
        .table {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table th {
            background: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Professional Alerts */
        .alert {
            border-radius: var(--radius-lg);
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
            backdrop-filter: blur(4px);
        }

        .sidebar-overlay.show {
            display: block;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle" title="Toggle Sidebar">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="app-container">
            <!-- Professional Sidebar -->
            <nav class="sidebar" id="sidebarMenu">
                <!-- Sidebar Header -->
                <div class="sidebar-header">
                    <a href="{{ route('dashboard') }}" class="sidebar-brand">
                        <i class="bi bi-bookmark-star"></i>
                        <span>{{ config('app.name') }}</span>
                    </a>
                    <div class="d-flex align-items-center justify-content-between mt-3">
                        <div class="text-xs text-muted">
                            Professional Edition
                        </div>
                        <button class="theme-toggle" id="themeToggle" title="Toggle theme">
                            <i class="bi bi-moon-fill"></i>
                        </button>
                    </div>
                </div>

                <!-- Sidebar Navigation -->
                <div class="sidebar-nav">
                    <!-- Main Navigation -->
                    <div class="nav-section">
                        <div class="nav-section-title">Main Menu</div>
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-1x2"></i>
                            <span>Dashboard</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('bookmarks.*') ? 'active' : '' }}" href="{{ route('bookmarks.index') }}">
                            <i class="bi bi-bookmark"></i>
                            <span>All Bookmarks</span>
                            <span class="nav-badge">{{ auth()->user()->bookmarks()->count() }}</span>
                        </a>
                        <a class="nav-link" href="{{ route('bookmarks.index', ['favorites' => 1]) }}">
                            <i class="bi bi-heart"></i>
                            <span>Favorites</span>
                            <span class="nav-badge">{{ auth()->user()->bookmarks()->where('favorite', true)->count() }}</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                            <i class="bi bi-folder2"></i>
                            <span>Categories</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('tags.*') ? 'active' : '' }}" href="{{ route('tags.index') }}">
                            <i class="bi bi-tags"></i>
                            <span>Tags</span>
                        </a>
                    </div>

                    <!-- Categories Navigation -->
                    <div class="nav-section">
                        <div class="nav-section-title d-flex justify-content-between align-items-center">
                            <span>Browse Categories</span>
                            <a href="{{ route('categories.create') }}" class="text-decoration-none" title="Add category">
                                <i class="bi bi-plus-circle" style="font-size: 0.875rem;"></i>
                            </a>
                        </div>
                        <div class="category-nav">
                            @foreach(auth()->user()->categories()->rootCategories()->limit(8)->get() as $category)
                            <a class="nav-link" href="{{ route('bookmarks.index', ['category' => $category->id]) }}">
                                <i class="bi bi-{{ $category->icon }} category-icon" style="color: {{ $category->color }}"></i>
                                <span>{{ $category->name }}</span>
                                <span class="nav-badge">{{ $category->bookmarks()->count() }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    @can('users.view')
                    <!-- Administration Section -->
                    <div class="nav-section">
                        <div class="nav-section-title">Administration</div>
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people"></i>
                            <span>Users</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                            <i class="bi bi-shield-check"></i>
                            <span>Roles</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" href="{{ route('admin.permissions.index') }}">
                            <i class="bi bi-key"></i>
                            <span>Permissions</span>
                        </a>
                    </div>
                    @endcan

                    <!-- Account Section -->
                    <div class="nav-section">
                        <div class="nav-section-title">Account</div>
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-circle"></i>
                            <span>Profile</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Professional Main Content -->
            <main class="main-content" id="mainContent">
                <!-- Professional Header -->
                <div class="main-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1>@yield('title')</h1>
                        <div class="header-actions">
                            @yield('header-actions')
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="content-wrapper px-4 pb-4">

                <!-- Flash Messages -->
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

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                    @yield('content')
                </div>
            </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme Toggle & Sidebar Toggle Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Theme Toggle
            const themeToggle = document.getElementById('themeToggle');
            const htmlElement = document.documentElement;

            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            htmlElement.setAttribute('data-bs-theme', savedTheme);
            updateThemeIcon(savedTheme);

            themeToggle.addEventListener('click', function() {
                const currentTheme = htmlElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';

                htmlElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });

            function updateThemeIcon(theme) {
                themeToggle.className = theme === 'light' ? 'bi bi-moon-fill theme-toggle' : 'bi bi-sun-fill theme-toggle';
            }

            // Sidebar Toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebarMenu');
            const mainContent = document.getElementById('mainContent');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Load saved sidebar state
            const savedSidebarState = localStorage.getItem('sidebarCollapsed') === 'true';
            if (savedSidebarState && window.innerWidth >= 768) {
                toggleSidebar(true);
            }

            sidebarToggle.addEventListener('click', function() {
                const isCollapsed = sidebar.classList.contains('collapsed') || sidebar.classList.contains('show');
                toggleSidebar(!isCollapsed);
            });

            function toggleSidebar(collapse) {
                if (window.innerWidth >= 768) {
                    // Desktop behavior
                    if (collapse) {
                        sidebar.classList.add('collapsed');
                        mainContent.classList.add('sidebar-collapsed');
                        sidebarToggle.classList.remove('sidebar-open');
                        sidebarToggle.querySelector('i').className = 'bi bi-list';
                        localStorage.setItem('sidebarCollapsed', 'true');
                    } else {
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.remove('sidebar-collapsed');
                        sidebarToggle.classList.add('sidebar-open');
                        sidebarToggle.querySelector('i').className = 'bi bi-chevron-left';
                        localStorage.setItem('sidebarCollapsed', 'false');
                    }
                } else {
                    // Mobile behavior
                    if (collapse || sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                        sidebarToggle.querySelector('i').className = 'bi bi-list';
                        document.body.style.overflow = '';
                    } else {
                        sidebar.classList.add('show');
                        sidebarOverlay.classList.add('show');
                        sidebarToggle.querySelector('i').className = 'bi bi-x-lg';
                        document.body.style.overflow = 'hidden';
                    }
                }
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                    if (isCollapsed) {
                        toggleSidebar(true);
                    } else {
                        toggleSidebar(false);
                    }
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('sidebar-collapsed');
                    sidebarToggle.classList.remove('sidebar-open');
                    sidebarToggle.querySelector('i').className = 'bi bi-list';
                }
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 768 && sidebar.classList.contains('show')) {
                    if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                        toggleSidebar(true);
                    }
                }
            });

            // Close sidebar when clicking overlay
            sidebarOverlay.addEventListener('click', function() {
                if (sidebar.classList.contains('show')) {
                    toggleSidebar(true);
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
