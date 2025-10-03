@extends('layouts.app')

@section('title', 'Dashboard')

@section('header-actions')
<a href="{{ route('bookmarks.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg"></i> Add Bookmark
</a>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($stats['total_bookmarks']) }}</h4>
                        <p class="mb-0">Total Bookmarks</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-bookmark-fill display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($stats['favorites']) }}</h4>
                        <p class="mb-0">Favorites</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-heart-fill display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($stats['categories']) }}</h4>
                        <p class="mb-0">Categories</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-folder-fill display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($stats['total_visits']) }}</h4>
                        <p class="mb-0">Total Visits</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-eye-fill display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Bookmarks -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Bookmarks</h5>
                <a href="{{ route('bookmarks.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @forelse($recentBookmarks as $bookmark)
                <div class="d-flex align-items-center mb-3">
                    @if($bookmark->favicon)
                        <img src="{{ $bookmark->favicon }}" alt="Favicon" class="favicon me-2" onerror="this.style.display='none'">
                    @endif
                    <div class="flex-grow-1">
                        <h6 class="mb-1">
                            <a href="{{ route('bookmarks.show', $bookmark) }}" class="text-decoration-none">
                                {{ Str::limit($bookmark->title, 40) }}
                            </a>
                        </h6>
                        <small class="text-muted">{{ $bookmark->domain }}</small>
                        @if($bookmark->category)
                            <span class="badge bg-primary ms-2">{{ $bookmark->category->name }}</span>
                        @endif
                    </div>
                    <small class="text-muted">{{ $bookmark->created_at->diffForHumans() }}</small>
                </div>
                @empty
                <p class="text-muted mb-0">No bookmarks yet. <a href="{{ route('bookmarks.create') }}">Create your first bookmark</a>!</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Most Visited -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Most Visited</h5>
            </div>
            <div class="card-body">
                @forelse($popularBookmarks as $bookmark)
                <div class="d-flex align-items-center mb-3">
                    @if($bookmark->favicon)
                        <img src="{{ $bookmark->favicon }}" alt="Favicon" class="favicon me-2" onerror="this.style.display='none'">
                    @endif
                    <div class="flex-grow-1">
                        <h6 class="mb-1">
                            <a href="{{ route('bookmarks.visit', $bookmark) }}" target="_blank" class="text-decoration-none">
                                {{ Str::limit($bookmark->title, 40) }}
                            </a>
                        </h6>
                        <small class="text-muted">{{ $bookmark->domain }}</small>
                    </div>
                    <span class="badge bg-secondary">{{ $bookmark->visits }} visits</span>
                </div>
                @empty
                <p class="text-muted mb-0">No visits yet. Start exploring your bookmarks!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Categories -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Top Categories</h5>
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
            <div class="card-body">
                @forelse($topCategories as $category)
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-{{ $category->icon }}" style="color: {{ $category->color }}"></i>
                        <span class="ms-2">{{ $category->name }}</span>
                    </div>
                    <span class="badge bg-secondary">{{ $category->bookmarks_count }}</span>
                </div>
                @empty
                <p class="text-muted mb-0">No categories yet. <a href="{{ route('categories.create') }}">Create one</a>!</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tag Cloud -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Popular Tags</h5>
                <a href="{{ route('tags.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="tag-cloud">
                    @forelse($topTags as $tag)
                        <a href="{{ route('bookmarks.index', ['tag' => $tag->slug]) }}"
                           class="badge bg-secondary text-decoration-none me-1 mb-1">
                            {{ $tag->name }} ({{ $tag->usage_count }})
                        </a>
                    @empty
                        <p class="text-muted mb-0">No tags yet. Add some tags to your bookmarks!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($recentActivities as $activity)
                <div class="d-flex align-items-start mb-3">
                    <div class="activity-icon me-2">
                        @switch($activity->action)
                            @case('created')
                                <i class="bi bi-plus-circle text-success"></i>
                                @break
                            @case('updated')
                                <i class="bi bi-pencil text-primary"></i>
                                @break
                            @case('deleted')
                                <i class="bi bi-trash text-danger"></i>
                                @break
                            @case('favorited')
                                <i class="bi bi-heart-fill text-danger"></i>
                                @break
                            @case('visited')
                                <i class="bi bi-eye text-info"></i>
                                @break
                            @default
                                <i class="bi bi-activity text-muted"></i>
                        @endswitch
                    </div>
                    <div class="flex-grow-1">
                        <div class="activity-text">
                            <span class="fw-bold">{{ ucfirst($activity->action) }}</span>
                            @if($activity->subject)
                                <span>{{ Str::limit($activity->subject->title ?? 'item', 30) }}</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0">No recent activity.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('bookmarks.create') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="bi bi-plus-lg d-block mb-2"></i>
                            Add Bookmark
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('categories.create') }}" class="btn btn-outline-secondary btn-lg w-100">
                            <i class="bi bi-folder-plus d-block mb-2"></i>
                            New Category
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('bookmarks.index', ['favorites' => 1]) }}" class="btn btn-outline-danger btn-lg w-100">
                            <i class="bi bi-heart d-block mb-2"></i>
                            View Favorites
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('bookmarks.index', ['sort' => 'visits', 'direction' => 'desc']) }}" class="btn btn-outline-info btn-lg w-100">
                            <i class="bi bi-graph-up d-block mb-2"></i>
                            Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
