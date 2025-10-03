{{--
/**
 * ================================================================================
 * BOOKMARK INDEX VIEW - ADVANCED MANAGEMENT INTERFACE
 * ================================================================================
 *
 * 🏢 VENDOR: Eastlink Cloud Pvt. Ltd.
 * 👨‍💻 AUTHOR: Developer Team
 * 📅 CREATED: October 2025
 * 📧 CONTACT: puran@eastlink.net.np
 * 📞 PHONE: +977-01-4101181
 * 📱 DEVELOPER: +977-9801901140
 * 💼 BUSINESS: +977-9801901141
 * 🏢 ADDRESS: Tripureshwor, Kathmandu, Nepal
 *
 * 📋 DESCRIPTION:
 * Advanced bookmark management interface with multiple view modes,
 * sophisticated filtering, sortable tables, and bulk operations.
 *
 * 🎯 FEATURES:
 * - Multiple View Modes (Grid, Table, List)
 * - Advanced Search & Filtering
 * - Sortable Table Headers (A-Z, Z-A)
 * - Bulk Operations & Selection
 * - Real-time Statistics
 * - Responsive Design
 * - Professional UI/UX
 *
 * 📊 SUPPORTED DATA VOLUME:
 * - Optimized for 100+ bookmarks
 * - Efficient pagination
 * - Fast search performance
 * - Scalable architecture
 *
 * ⚖️ LICENSE: Commercial Enterprise License
 * ================================================================================
 */
--}}

@extends('layouts.app')

@section('title', 'Bookmarks')

@section('header-actions')
<div class="d-flex gap-2 flex-wrap">
    <!-- Add New Bookmark -->
    <a href="{{ route('bookmarks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Bookmark
    </a>

    <!-- View Mode Toggle -->
    <div class="btn-group" role="group">
        <input type="radio" class="btn-check" name="viewMode" id="gridView" autocomplete="off" {{ request('view', 'grid') == 'grid' ? 'checked' : '' }}>
        <label class="btn btn-outline-primary" for="gridView" onclick="switchView('grid')">
            <i class="bi bi-grid-3x3-gap"></i> Grid
        </label>

        <input type="radio" class="btn-check" name="viewMode" id="tableView" autocomplete="off" {{ request('view') == 'table' ? 'checked' : '' }}>
        <label class="btn btn-outline-primary" for="tableView" onclick="switchView('table')">
            <i class="bi bi-table"></i> Table
        </label>

        <input type="radio" class="btn-check" name="viewMode" id="listView" autocomplete="off" {{ request('view') == 'list' ? 'checked' : '' }}>
        <label class="btn btn-outline-primary" for="listView" onclick="switchView('list')">
            <i class="bi bi-list-ul"></i> List
        </label>
    </div>

    <!-- Advanced Filters -->
    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
        <i class="bi bi-funnel"></i> Advanced Filters
        @if(request()->hasAny(['search', 'category', 'tag', 'status', 'favorite', 'private', 'date_from', 'date_to']))
            <span class="badge bg-danger ms-1">Active</span>
        @endif
    </button>

    <!-- Quick Actions -->
    <div class="btn-group">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-lightning"></i> Quick Actions
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="javascript:selectAll()"><i class="bi bi-check-all"></i> Select All</a></li>
            <li><a class="dropdown-item" href="javascript:selectNone()"><i class="bi bi-x-square"></i> Select None</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="javascript:exportBookmarks()"><i class="bi bi-download"></i> Export Selected</a></li>
            <li><a class="dropdown-item" href="javascript:importBookmarks()"><i class="bi bi-upload"></i> Import Bookmarks</a></li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<!-- Advanced Filters -->
<div class="collapse mb-4" id="filtersCollapse">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-funnel"></i> Advanced Filters & Search</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearAllFilters()">
                    <i class="bi bi-x-circle"></i> Clear All
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('bookmarks.index') }}" id="filterForm">
                <!-- Keep current view mode -->
                <input type="hidden" name="view" value="{{ request('view', 'grid') }}">

                <!-- Search & Quick Filters Row -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="search" class="form-label">🔍 Search in Title, Description, URL</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Type to search..." autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('search').value=''; document.getElementById('filterForm').submit();">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="quickFilter" class="form-label">⚡ Quick Filters</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="setQuickFilter('favorite', '1')">
                                <i class="bi bi-heart-fill"></i> Favorites
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="setQuickFilter('private', '1')">
                                <i class="bi bi-lock"></i> Private
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="setQuickFilter('recent', '7')">
                                <i class="bi bi-clock"></i> Recent (7 days)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="setQuickFilter('popular', '1')">
                                <i class="bi bi-eye"></i> Most Visited
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Detailed Filters Row -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="category" class="form-label">📁 Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    <i class="bi bi-{{ $category->icon ?? 'folder' }}"></i> {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tag" class="form-label">🏷️ Tags</label>
                        <input type="text" class="form-control" id="tag" name="tag"
                               value="{{ request('tag') }}" placeholder="Enter tag name">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">📊 Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Active</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                            <option value="broken" {{ request('status') == 'broken' ? 'selected' : '' }}>💔 Broken Links</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="domain" class="form-label">🌐 Domain</label>
                        <input type="text" class="form-control" id="domain" name="domain"
                               value="{{ request('domain') }}" placeholder="e.g., github.com">
                    </div>
                </div>

                <!-- Advanced Options Row -->
                <div class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label class="form-label">❤️ Favorites</label>
                        <select class="form-select" name="favorite">
                            <option value="">All</option>
                            <option value="1" {{ request('favorite') == '1' ? 'selected' : '' }}>Favorites Only</option>
                            <option value="0" {{ request('favorite') == '0' ? 'selected' : '' }}>Non-Favorites</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">🔒 Privacy</label>
                        <select class="form-select" name="private">
                            <option value="">All</option>
                            <option value="1" {{ request('private') == '1' ? 'selected' : '' }}>Private Only</option>
                            <option value="0" {{ request('private') == '0' ? 'selected' : '' }}>Public Only</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">📅 Date From</label>
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">📅 Date To</label>
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">📈 Sort By</label>
                        <select class="form-select" name="sort">
                            <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>📅 Date Added</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>🔤 Title A-Z</option>
                            <option value="visits" {{ request('sort') == 'visits' ? 'selected' : '' }}>👁️ Most Visited</option>
                            <option value="updated_at" {{ request('sort') == 'updated_at' ? 'selected' : '' }}>🔄 Recently Updated</option>
                            <option value="domain" {{ request('sort') == 'domain' ? 'selected' : '' }}>🌐 Domain</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">📊 Per Page</label>
                        <select class="form-select" name="per_page">
                            <option value="12" {{ request('per_page', '12') == '12' ? 'selected' : '' }}>12</option>
                            <option value="24" {{ request('per_page') == '24' ? 'selected' : '' }}>24</option>
                            <option value="48" {{ request('per_page') == '48' ? 'selected' : '' }}>48</option>
                            <option value="96" {{ request('per_page') == '96' ? 'selected' : '' }}>96</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Apply Filters
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearAllFilters()">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="saveCurrentFilters()">
                            <i class="bi bi-bookmark"></i> Save Filter Preset
                        </button>
                    </div>
                    <div class="text-muted">
                        <small>💡 Tip: Use Ctrl+F for instant search within results</small>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Actions -->
<div class="d-none" id="bulkActions">
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <span><span id="selectedCount">0</span> bookmarks selected</span>
        <form method="POST" action="{{ route('bookmarks.bulk') }}" class="d-inline">
            @csrf
            <input type="hidden" name="bookmarks" id="selectedBookmarks">
            <div class="btn-group" role="group">
                <button type="submit" name="action" value="favorite" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-heart"></i> Favorite
                </button>
                <button type="submit" name="action" value="archive" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-archive"></i> Archive
                </button>
                <button type="submit" name="action" value="delete" class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Are you sure you want to delete selected bookmarks?')">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Results Summary -->
@if($bookmarks->count() > 0)
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="text-muted">
        <strong>{{ $bookmarks->total() }}</strong> bookmarks found
        @if(request()->hasAny(['search', 'category', 'tag', 'status', 'favorite', 'private']))
            (filtered from {{ auth()->user()->bookmarks()->count() }} total)
        @endif
    </div>
    <div class="d-flex align-items-center gap-2">
        <small class="text-muted">View:</small>
        <div class="btn-group btn-group-sm" role="group">
            <input type="radio" class="btn-check" name="viewToggle" id="viewGrid" autocomplete="off" {{ request('view', 'grid') == 'grid' ? 'checked' : '' }}>
            <label class="btn btn-outline-secondary" for="viewGrid" onclick="switchView('grid')">
                <i class="bi bi-grid-3x3-gap"></i>
            </label>
            <input type="radio" class="btn-check" name="viewToggle" id="viewTable" autocomplete="off" {{ request('view') == 'table' ? 'checked' : '' }}>
            <label class="btn btn-outline-secondary" for="viewTable" onclick="switchView('table')">
                <i class="bi bi-table"></i>
            </label>
            <input type="radio" class="btn-check" name="viewToggle" id="viewList" autocomplete="off" {{ request('view') == 'list' ? 'checked' : '' }}>
            <label class="btn btn-outline-secondary" for="viewList" onclick="switchView('list')">
                <i class="bi bi-list-ul"></i>
            </label>
        </div>
    </div>
</div>

<!-- Table View for Large Collections -->
@if(request('view') == 'table')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="bookmarksTable">
                <thead class="table-light sticky-top">
                    <tr>
                        <th width="40">
                            <input type="checkbox" class="form-check-input" id="selectAllTable" onchange="toggleAllTableSelection()">
                        </th>
                        <th width="50">Icon</th>
                        <th class="sortable-header" onclick="sortTable('title')" title="Click to sort by title (A-Z / Z-A)">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Title & URL</span>
                                <div class="sort-icons">
                                    @if(request('sort') == 'title')
                                        @if(request('direction', 'desc') == 'asc')
                                            <i class="bi bi-sort-alpha-down text-primary"></i>
                                        @else
                                            <i class="bi bi-sort-alpha-up text-primary"></i>
                                        @endif
                                    @else
                                        <i class="bi bi-arrow-down-up text-muted"></i>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <th width="120" class="sortable-header" onclick="sortTable('category')" title="Click to sort by category (A-Z / Z-A)">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Category</span>
                                <div class="sort-icons">
                                    @if(request('sort') == 'category')
                                        @if(request('direction', 'desc') == 'asc')
                                            <i class="bi bi-sort-alpha-down text-primary"></i>
                                        @else
                                            <i class="bi bi-sort-alpha-up text-primary"></i>
                                        @endif
                                    @else
                                        <i class="bi bi-arrow-down-up text-muted"></i>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <th width="150">Tags</th>
                        <th width="80" class="sortable-header" onclick="sortTable('visits')" title="Click to sort by visit count (High to Low / Low to High)">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Stats</span>
                                <div class="sort-icons">
                                    @if(request('sort') == 'visits')
                                        @if(request('direction', 'desc') == 'asc')
                                            <i class="bi bi-sort-numeric-down text-primary"></i>
                                        @else
                                            <i class="bi bi-sort-numeric-up text-primary"></i>
                                        @endif
                                    @else
                                        <i class="bi bi-arrow-down-up text-muted"></i>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <th width="100">Status</th>
                        <th width="120" class="sortable-header" onclick="sortTable('created_at')" title="Click to sort by date (Newest First / Oldest First)">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Date</span>
                                <div class="sort-icons">
                                    @if(request('sort') == 'created_at' || !request('sort'))
                                        @if(request('direction', 'desc') == 'asc')
                                            <i class="bi bi-sort-down text-primary"></i>
                                        @else
                                            <i class="bi bi-sort-up text-primary"></i>
                                        @endif
                                    @else
                                        <i class="bi bi-arrow-down-up text-muted"></i>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookmarks as $bookmark)
                    <tr class="bookmark-row" data-bookmark-id="{{ $bookmark->id }}">
                        <td>
                            <input type="checkbox" class="form-check-input bookmark-checkbox" value="{{ $bookmark->id }}">
                        </td>
                        <td>
                            @if($bookmark->favicon)
                                <img src="{{ $bookmark->favicon }}" alt="Favicon" class="favicon" width="16" height="16" onerror="this.style.display='none'">
                            @else
                                <i class="bi bi-globe text-muted"></i>
                            @endif
                        </td>
                        <td>
                            <div class="bookmark-info">
                                <h6 class="mb-1">
                                    <a href="{{ route('bookmarks.show', $bookmark) }}" class="text-decoration-none fw-medium">
                                        {{ Str::limit($bookmark->title, 60) }}
                                    </a>
                                    @if($bookmark->favorite)
                                        <i class="bi bi-heart-fill text-danger ms-1" title="Favorite"></i>
                                    @endif
                                    @if($bookmark->private)
                                        <i class="bi bi-lock text-warning ms-1" title="Private"></i>
                                    @endif
                                </h6>
                                <div class="small text-muted">
                                    <i class="bi bi-link-45deg"></i>
                                    <a href="{{ $bookmark->url }}" target="_blank" class="text-muted text-decoration-none">
                                        {{ Str::limit($bookmark->domain, 30) }}
                                    </a>
                                </div>
                                @if($bookmark->description)
                                    <div class="small text-muted mt-1">
                                        {{ Str::limit($bookmark->description, 80) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($bookmark->category)
                                <span class="badge bg-primary">
                                    <i class="bi bi-{{ $bookmark->category->icon ?? 'folder' }}"></i>
                                    {{ $bookmark->category->name }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($bookmark->tags->count() > 0)
                                <div class="tag-cloud">
                                    @foreach($bookmark->tags->take(3) as $tag)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $tag->name }}</span>
                                    @endforeach
                                    @if($bookmark->tags->count() > 3)
                                        <span class="badge bg-light text-dark">+{{ $bookmark->tags->count() - 3 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column small">
                                <span><i class="bi bi-eye"></i> {{ $bookmark->visits }}</span>
                                <span class="text-muted">visits</span>
                            </div>
                        </td>
                        <td>
                            @if($bookmark->is_broken)
                                <span class="badge bg-danger">Broken</span>
                            @elseif($bookmark->archived_at)
                                <span class="badge bg-secondary">Archived</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column small text-muted">
                                <span title="{{ $bookmark->created_at->format('M j, Y g:i A') }}">
                                    {{ $bookmark->created_at->diffForHumans() }}
                                </span>
                                @if($bookmark->updated_at != $bookmark->created_at)
                                    <span class="text-info" title="Updated {{ $bookmark->updated_at->format('M j, Y g:i A') }}">
                                        Updated {{ $bookmark->updated_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('bookmarks.visit', $bookmark) }}" target="_blank"
                                   class="btn btn-outline-primary btn-sm" title="Visit">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                                <a href="{{ route('bookmarks.edit', $bookmark) }}"
                                   class="btn btn-outline-secondary btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                        onclick="deleteBookmark({{ $bookmark->id }}, '{{ addslashes($bookmark->title) }}')" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- List View for Compact Display -->
@elseif(request('view') == 'list')
<div class="card">
    <div class="card-body">
        <div class="list-group list-group-flush">
            @foreach($bookmarks as $bookmark)
            <div class="list-group-item border-0 px-0 py-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-start gap-3 flex-grow-1">
                        <input type="checkbox" class="form-check-input bookmark-checkbox mt-2" value="{{ $bookmark->id }}">

                        <div class="d-flex align-items-center gap-2">
                            @if($bookmark->favicon)
                                <img src="{{ $bookmark->favicon }}" alt="Favicon" class="favicon" width="20" height="20" onerror="this.style.display='none'">
                            @else
                                <i class="bi bi-globe text-muted"></i>
                            @endif
                        </div>

                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h6 class="mb-0">
                                    <a href="{{ route('bookmarks.show', $bookmark) }}" class="text-decoration-none">
                                        {{ $bookmark->title }}
                                    </a>
                                </h6>
                                @if($bookmark->favorite)
                                    <i class="bi bi-heart-fill text-danger" title="Favorite"></i>
                                @endif
                                @if($bookmark->private)
                                    <i class="bi bi-lock text-warning" title="Private"></i>
                                @endif
                            </div>

                            <div class="text-muted small mb-2">
                                <a href="{{ $bookmark->url }}" target="_blank" class="text-muted text-decoration-none">
                                    {{ $bookmark->domain }}
                                </a>
                                @if($bookmark->description)
                                    • {{ Str::limit($bookmark->description, 100) }}
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-3 small">
                                @if($bookmark->category)
                                    <span class="badge bg-primary">{{ $bookmark->category->name }}</span>
                                @endif

                                @if($bookmark->tags->count() > 0)
                                    <div class="tag-cloud">
                                        @foreach($bookmark->tags->take(5) as $tag)
                                            <span class="badge bg-secondary me-1">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <span class="text-muted">
                                    <i class="bi bi-eye"></i> {{ $bookmark->visits }} visits
                                </span>

                                <span class="text-muted">
                                    {{ $bookmark->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('bookmarks.visit', $bookmark) }}" target="_blank"
                               class="btn btn-outline-primary" title="Visit">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <a href="{{ route('bookmarks.edit', $bookmark) }}"
                               class="btn btn-outline-secondary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger"
                                    onclick="deleteBookmark({{ $bookmark->id }}, '{{ addslashes($bookmark->title) }}')" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Grid View (Default) -->
@else
<div class="row">
<div class="row">
    @foreach($bookmarks as $bookmark)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card bookmark-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="form-check">
                        <input class="form-check-input bookmark-checkbox" type="checkbox" value="{{ $bookmark->id }}">
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('bookmarks.visit', $bookmark) }}" target="_blank">
                                <i class="bi bi-box-arrow-up-right"></i> Visit
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('bookmarks.edit', $bookmark) }}">
                                <i class="bi bi-pencil"></i> Edit
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('bookmarks.destroy', $bookmark) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger"
                                            onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-2">
                    @if($bookmark->favicon)
                        <img src="{{ $bookmark->favicon }}" alt="Favicon" class="favicon" onerror="this.style.display='none'">
                    @endif
                    <h6 class="card-title mb-0 text-truncate">
                        <a href="{{ route('bookmarks.show', $bookmark) }}" class="text-decoration-none">
                            {{ $bookmark->title }}
                        </a>
                    </h6>
                </div>

                @if($bookmark->description)
                <p class="card-text text-muted small">{{ Str::limit($bookmark->description, 100) }}</p>
                @endif

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <small class="text-muted">{{ $bookmark->domain }}</small>
                    <div class="d-flex align-items-center">
                        @if($bookmark->favorite)
                            <i class="bi bi-heart-fill text-danger me-2" title="Favorite"></i>
                        @endif
                        @if($bookmark->private)
                            <i class="bi bi-lock text-warning me-2" title="Private"></i>
                        @endif
                        <button class="btn btn-sm btn-link p-0 favorite-toggle"
                                data-bookmark-id="{{ $bookmark->id }}"
                                data-favorite="{{ $bookmark->favorite ? 'true' : 'false' }}">
                            <i class="bi bi-heart{{ $bookmark->favorite ? '-fill text-danger' : '' }}"></i>
                        </button>
                    </div>
                </div>

                @if($bookmark->tags->count() > 0)
                <div class="tag-cloud mb-2">
                    @foreach($bookmark->tags as $tag)
                        <a href="{{ route('bookmarks.index', ['tag' => $tag->slug]) }}"
                           class="badge bg-secondary text-decoration-none">{{ $tag->name }}</a>
                    @endforeach
                </div>
                @endif

                @if($bookmark->category)
                <div class="mb-2">
                    <a href="{{ route('bookmarks.index', ['category' => $bookmark->category_id]) }}"
                       class="badge bg-primary text-decoration-none">
                        <i class="bi bi-{{ $bookmark->category->icon }}"></i> {{ $bookmark->category->name }}
                    </a>
                </div>
                @endif

                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-eye"></i> {{ $bookmark->visits }} visits
                    </small>
                    <small class="text-muted">{{ $bookmark->created_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $bookmarks->appends(request()->query())->links() }}
</div>

@else
<div class="text-center py-5">
    <i class="bi bi-bookmark display-1 text-muted"></i>
    <h4 class="mt-3">No bookmarks found</h4>
    <p class="text-muted">Start building your bookmark collection</p>
    <a href="{{ route('bookmarks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Your First Bookmark
    </a>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeBookmarkManagement();
});

function initializeBookmarkManagement() {
    // Bookmark selection
    const checkboxes = document.querySelectorAll('.bookmark-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const selectedBookmarks = document.getElementById('selectedBookmarks');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked);
        const count = selected.length;

        if (count > 0) {
            bulkActions?.classList.remove('d-none');
            if (selectedCount) selectedCount.textContent = count;
            if (selectedBookmarks) selectedBookmarks.value = JSON.stringify(selected.map(cb => cb.value));
        } else {
            bulkActions?.classList.add('d-none');
        }
    }

    // Favorite toggle
    document.querySelectorAll('.favorite-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const bookmarkId = this.dataset.bookmarkId;
            const isFavorite = this.dataset.favorite === 'true';

            fetch(`/bookmarks/${bookmarkId}/favorite`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                const icon = this.querySelector('i');
                if (data.favorite) {
                    icon.className = 'bi bi-heart-fill text-danger';
                    this.dataset.favorite = 'true';
                } else {
                    icon.className = 'bi bi-heart';
                    this.dataset.favorite = 'false';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Auto-submit search on typing (debounced)
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    document.getElementById('filterForm').submit();
                }
            }, 500);
        });
    }
}

// View switching functionality
function switchView(viewType) {
    const url = new URL(window.location);
    url.searchParams.set('view', viewType);
    window.location.href = url.toString();
}

// Selection functions
function selectAll() {
    document.querySelectorAll('.bookmark-checkbox').forEach(cb => cb.checked = true);
    updateBulkActions();
}

function selectNone() {
    document.querySelectorAll('.bookmark-checkbox').forEach(cb => cb.checked = false);
    updateBulkActions();
}

function toggleAllTableSelection() {
    const selectAll = document.getElementById('selectAllTable');
    const checkboxes = document.querySelectorAll('.bookmark-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

// Quick filter functions
function setQuickFilter(type, value) {
    const form = document.getElementById('filterForm');
    const url = new URL(form.action);

    // Clear existing filters
    url.searchParams.delete('favorite');
    url.searchParams.delete('private');
    url.searchParams.delete('date_from');
    url.searchParams.delete('sort');

    switch(type) {
        case 'favorite':
            url.searchParams.set('favorite', '1');
            break;
        case 'private':
            url.searchParams.set('private', '1');
            break;
        case 'recent':
            const date = new Date();
            date.setDate(date.getDate() - parseInt(value));
            url.searchParams.set('date_from', date.toISOString().split('T')[0]);
            break;
        case 'popular':
            url.searchParams.set('sort', 'visits');
            break;
    }

    window.location.href = url.toString();
}

// Clear all filters
function clearAllFilters() {
    const url = new URL(window.location);
    // Keep only the view parameter
    const view = url.searchParams.get('view') || 'grid';
    url.search = '';
    url.searchParams.set('view', view);
    window.location.href = url.toString();
}

// Save filter preset (localStorage)
function saveCurrentFilters() {
    const url = new URL(window.location);
    const filters = Object.fromEntries(url.searchParams);
    const presetName = prompt('Enter a name for this filter preset:');
    if (presetName) {
        const presets = JSON.parse(localStorage.getItem('bookmarkFilterPresets') || '{}');
        presets[presetName] = filters;
        localStorage.setItem('bookmarkFilterPresets', JSON.stringify(presets));
        alert(`Filter preset "${presetName}" saved!`);
    }
}

// Delete bookmark function
function deleteBookmark(bookmarkId, title) {
    if (confirm(`Are you sure you want to delete "${title}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/bookmarks/${bookmarkId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Export/Import functions
function exportBookmarks() {
    const selected = Array.from(document.querySelectorAll('.bookmark-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('Please select bookmarks to export');
        return;
    }
    // Implementation would create downloadable file
    alert('Export functionality would be implemented here');
}

function importBookmarks() {
    // Implementation would show import dialog
    alert('Import functionality would be implemented here');
}

// Table sorting (if needed)
function sortTable(column) {
    const url = new URL(window.location);
    const currentSort = url.searchParams.get('sort');
    const direction = url.searchParams.get('direction') || 'asc';

    if (currentSort === column && direction === 'asc') {
        url.searchParams.set('direction', 'desc');
    } else {
        url.searchParams.set('direction', 'asc');
    }

    url.searchParams.set('sort', column);
    window.location.href = url.toString();
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K for search focus
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        document.getElementById('search')?.focus();
    }

    // Escape to clear search
    if (e.key === 'Escape') {
        const searchInput = document.getElementById('search');
        if (searchInput && searchInput === document.activeElement) {
            searchInput.value = '';
            searchInput.blur();
        }
    }
});

// Update bulk actions function (needs to be global)
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.bookmark-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const selectedBookmarks = document.getElementById('selectedBookmarks');

    const selected = Array.from(checkboxes).filter(cb => cb.checked);
    const count = selected.length;

    if (count > 0) {
        bulkActions?.classList.remove('d-none');
        if (selectedCount) selectedCount.textContent = count;
        if (selectedBookmarks) selectedBookmarks.value = JSON.stringify(selected.map(cb => cb.value));
    } else {
        bulkActions?.classList.add('d-none');
    }
}
</script>

<style>
/* Enhanced table styling */
#bookmarksTable {
    font-size: 0.9rem;
}

#bookmarksTable .favicon {
    width: 16px;
    height: 16px;
    object-fit: contain;
}

.bookmark-info h6 {
    font-size: 0.95rem;
    line-height: 1.3;
}

.bookmark-row:hover {
    background-color: rgba(0,123,255,0.05);
}

.tag-cloud .badge {
    font-size: 0.7rem;
}

/* Sticky table header */
.sticky-top {
    position: sticky;
    top: 0;
    z-index: 1020;
}

/* List view enhancements */
.list-group-item:hover {
    background-color: rgba(0,123,255,0.02);
}

.min-width-0 {
    min-width: 0;
}

/* Filter animations */
#filtersCollapse .card {
    border: 1px solid #e9ecef;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

/* Quick filter buttons */
.btn-outline-danger:not(:hover):not(:focus):not(:active) {
    color: #dc3545;
}

.btn-outline-warning:not(:hover):not(:focus):not(:active) {
    color: #fd7e14;
}

.btn-outline-success:not(:hover):not(:focus):not(:active) {
    color: #198754;
}

.btn-outline-info:not(:hover):not(:focus):not(:active) {
    color: #0dcaf0;
}

/* Sortable table headers */
.sortable-header {
    cursor: pointer;
    user-select: none;
    transition: background-color 0.2s ease;
}

.sortable-header:hover {
    background-color: rgba(0,123,255,0.08) !important;
}

.sortable-header .sort-icons i {
    font-size: 0.8rem;
    transition: color 0.2s ease;
}

.sortable-header:hover .sort-icons i {
    color: #0d6efd !important;
}

/* Active sort state */
.sortable-header .sort-icons i.text-primary {
    font-weight: bold;
}

/* Sort direction indicators */
.bi-sort-alpha-down:before { content: "\f21e"; }
.bi-sort-alpha-up:before { content: "\f21f"; }
.bi-sort-numeric-down:before { content: "\f223"; }
.bi-sort-numeric-up:before { content: "\f224"; }
.bi-sort-down:before { content: "\f221"; }
.bi-sort-up:before { content: "\f222"; }
.bi-arrow-down-up:before { content: "\f166"; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.8rem;
    }

    .btn-group-sm .btn {
        padding: 0.125rem 0.25rem;
    }

    .sortable-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 2px;
    }

    .sortable-header .sort-icons {
        align-self: flex-end;
    }
}
</style>
@endpush
