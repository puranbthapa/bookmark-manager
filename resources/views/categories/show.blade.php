@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="row">
    <!-- Category Header -->
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-{{ $category->icon }}"
                               style="color: {{ $category->color }}; font-size: 3rem;"></i>
                            <div class="ms-3">
                                <h2 class="mb-1">{{ $category->name }}</h2>
                                @if($category->parent)
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0">
                                            <li class="breadcrumb-item">
                                                <a href="{{ route('categories.show', $category->parent) }}">
                                                    {{ $category->parent->name }}
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item active">{{ $category->name }}</li>
                                        </ol>
                                    </nav>
                                @endif
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-bookmark text-primary"></i>
                                    <span class="ms-2">{{ $category->bookmarks_count }} Bookmarks</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-folder text-success"></i>
                                    <span class="ms-2">{{ $category->children_count }} Subcategories</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar text-info"></i>
                                    <span class="ms-2">Created {{ $category->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        @can('update', $category)
                            <a href="{{ route('categories.edit', $category) }}"
                               class="btn btn-outline-primary me-2">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        @endcan

                        @can('delete', $category)
                            @if($category->bookmarks_count == 0 && $category->children_count == 0)
                                <button type="button" class="btn btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subcategories -->
    @if($category->children->count() > 0)
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-folder-fill"></i> Subcategories ({{ $category->children->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($category->children as $child)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-{{ $child->icon }}"
                                       style="color: {{ $child->color }}; font-size: 2rem;"></i>
                                    <div class="ms-3">
                                        <h6 class="mb-1">
                                            <a href="{{ route('categories.show', $child) }}"
                                               class="text-decoration-none">
                                                {{ $child->name }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            {{ $child->bookmarks_count }} bookmarks
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        {{ $child->created_at->diffForHumans() }}
                                    </small>
                                    <a href="{{ route('categories.show', $child) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-0">Quick Actions</h6>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('bookmarks.create', ['category_id' => $category->id]) }}"
                           class="btn btn-primary me-2">
                            <i class="bi bi-plus-circle"></i> Add Bookmark
                        </a>
                        <a href="{{ route('categories.create', ['parent_id' => $category->id]) }}"
                           class="btn btn-outline-success">
                            <i class="bi bi-folder-plus"></i> Add Subcategory
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookmarks -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-bookmark-fill"></i>
                    Bookmarks ({{ $bookmarks->total() }})
                </h5>
                <div class="d-flex align-items-center">
                    <!-- View Toggle -->
                    <div class="btn-group me-3" role="group">
                        <input type="radio" class="btn-check" name="view" id="grid-view" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="grid-view">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </label>
                        <input type="radio" class="btn-check" name="view" id="list-view">
                        <label class="btn btn-outline-secondary btn-sm" for="list-view">
                            <i class="bi bi-list-ul"></i>
                        </label>
                    </div>

                    <!-- Sort -->
                    <select class="form-select form-select-sm" id="sortBy" style="width: auto;">
                        <option value="created_at">Newest First</option>
                        <option value="title">Title A-Z</option>
                        <option value="updated_at">Recently Updated</option>
                        <option value="clicks">Most Visited</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                @if($bookmarks->count() > 0)
                    <!-- Grid View -->
                    <div id="grid-container" class="row g-3">
                        @foreach($bookmarks as $bookmark)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 bookmark-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-3">
                                        @if($bookmark->favicon_url)
                                            <img src="{{ $bookmark->favicon_url }}"
                                                 alt="Favicon" class="favicon me-2">
                                        @else
                                            <i class="bi bi-globe2 text-muted me-2"></i>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1">
                                                <a href="{{ $bookmark->url }}"
                                                   target="_blank"
                                                   class="text-decoration-none bookmark-link"
                                                   data-bookmark-id="{{ $bookmark->id }}">
                                                    {{ Str::limit($bookmark->title, 50) }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                {{ parse_url($bookmark->url, PHP_URL_HOST) }}
                                            </small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-ghost" type="button"
                                                    data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @can('update', $bookmark)
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('bookmarks.edit', $bookmark) }}">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                </li>
                                                @endcan
                                                @can('delete', $bookmark)
                                                <li>
                                                    <button class="dropdown-item text-danger"
                                                            onclick="deleteBookmark({{ $bookmark->id }})">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </div>

                                    @if($bookmark->description)
                                        <p class="card-text text-muted small mb-3">
                                            {{ Str::limit($bookmark->description, 100) }}
                                        </p>
                                    @endif

                                    <!-- Tags -->
                                    @if($bookmark->tags->count() > 0)
                                        <div class="mb-3">
                                            @foreach($bookmark->tags->take(3) as $tag)
                                                <span class="badge bg-secondary me-1">{{ $tag->name }}</span>
                                            @endforeach
                                            @if($bookmark->tags->count() > 3)
                                                <small class="text-muted">+{{ $bookmark->tags->count() - 3 }} more</small>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            {{ $bookmark->created_at->diffForHumans() }}
                                        </small>
                                        @if($bookmark->clicks > 0)
                                            <small class="text-muted">
                                                <i class="bi bi-eye"></i> {{ $bookmark->clicks }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- List View (Hidden by default) -->
                    <div id="list-container" class="d-none">
                        @foreach($bookmarks as $bookmark)
                        <div class="border-bottom py-3">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        @if($bookmark->favicon_url)
                                            <img src="{{ $bookmark->favicon_url }}"
                                                 alt="Favicon" class="favicon me-3">
                                        @else
                                            <i class="bi bi-globe2 text-muted me-3"></i>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ $bookmark->url }}"
                                                   target="_blank"
                                                   class="text-decoration-none bookmark-link"
                                                   data-bookmark-id="{{ $bookmark->id }}">
                                                    {{ $bookmark->title }}
                                                </a>
                                            </h6>
                                            <div class="text-muted small">
                                                {{ parse_url($bookmark->url, PHP_URL_HOST) }}
                                                @if($bookmark->description)
                                                    • {{ Str::limit($bookmark->description, 60) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    @if($bookmark->clicks > 0)
                                        <small class="text-muted">
                                            <i class="bi bi-eye"></i> {{ $bookmark->clicks }}
                                        </small>
                                    @endif
                                </div>
                                <div class="col-md-2 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-ghost" type="button"
                                                data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('update', $bookmark)
                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('bookmarks.edit', $bookmark) }}">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                            </li>
                                            @endcan
                                            @can('delete', $bookmark)
                                            <li>
                                                <button class="dropdown-item text-danger"
                                                        onclick="deleteBookmark({{ $bookmark->id }})">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($bookmarks->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $bookmarks->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-bookmark text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">No bookmarks yet</h5>
                        <p class="text-muted">Get started by adding your first bookmark to this category.</p>
                        <a href="{{ route('bookmarks.create', ['category_id' => $category->id]) }}"
                           class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Bookmark
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
@can('delete', $category)
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category <strong>{{ $category->name }}</strong>?</p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@push('styles')
<style>
.favicon {
    width: 16px;
    height: 16px;
    object-fit: contain;
}

.bookmark-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.bookmark-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.btn-ghost {
    border: none;
    background: none;
    color: #6c757d;
}

.btn-ghost:hover {
    background: rgba(0,0,0,0.05);
    color: #495057;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const gridContainer = document.getElementById('grid-container');
    const listContainer = document.getElementById('list-container');

    gridView.addEventListener('change', function() {
        if (this.checked) {
            gridContainer.classList.remove('d-none');
            listContainer.classList.add('d-none');
        }
    });

    listView.addEventListener('change', function() {
        if (this.checked) {
            gridContainer.classList.add('d-none');
            listContainer.classList.remove('d-none');
        }
    });

    // Track bookmark clicks
    document.querySelectorAll('.bookmark-link').forEach(link => {
        link.addEventListener('click', function() {
            const bookmarkId = this.dataset.bookmarkId;
            fetch(`/api/bookmarks/${bookmarkId}/click`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });
        });
    });
});

function deleteBookmark(bookmarkId) {
    if (confirm('Are you sure you want to delete this bookmark?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/bookmarks/${bookmarkId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
