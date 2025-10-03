@extends('layouts.app')

@section('title', $tag->name . ' - Tag')

@section('content')
<div class="row">
    <!-- Tag Header -->
    <div class="col-12">
        <div class="card mb-4" style="border-left: 6px solid {{ $tag->color }};">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="tag-icon me-3"
                                 style="width: 60px; height: 60px; background-color: {{ $tag->color }}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-tag-fill text-white" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h1 class="mb-1"># {{ $tag->name }}</h1>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('tags.index') }}">Tags</a>
                                        </li>
                                        <li class="breadcrumb-item active">{{ $tag->name }}</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-bookmark text-primary"></i>
                                    <span class="ms-2">{{ $userBookmarkCount }} Your Bookmarks</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-globe text-success"></i>
                                    <span class="ms-2">{{ $totalTagUsage }} Total Usage</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-percent text-info"></i>
                                    <span class="ms-2">{{ $tagUsagePercentage }}% Your Usage</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar text-warning"></i>
                                    <span class="ms-2">Created {{ $tag->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        @can('update', $tag)
                            <button type="button" class="btn btn-outline-primary me-2"
                                    onclick="editTag({{ $tag->id }}, '{{ $tag->name }}', '{{ $tag->color }}')">
                                <i class="bi bi-pencil"></i> Edit Tag
                            </button>
                        @endcan

                        @can('delete', $tag)
                            @if($userBookmarkCount == 0)
                                <button type="button" class="btn btn-outline-danger"
                                        onclick="deleteTag({{ $tag->id }}, '{{ $tag->name }}')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Tags -->
    @if($relatedTags->count() > 0)
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-diagram-3"></i> Related Tags
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($relatedTags as $relatedTag)
                        <a href="{{ route('tags.show', $relatedTag) }}"
                           class="badge text-decoration-none"
                           style="background-color: {{ $relatedTag->color }}; font-size: 0.8rem; padding: 0.5rem;">
                            # {{ $relatedTag->name }}
                            <span class="ms-1 opacity-75">({{ $relatedTag->bookmarks_count }})</span>
                        </a>
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
                        <a href="{{ route('bookmarks.create', ['tag' => $tag->name]) }}"
                           class="btn btn-primary me-2">
                            <i class="bi bi-plus-circle"></i> Add Bookmark with this Tag
                        </a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#tagStatsModal">
                            <i class="bi bi-graph-up"></i> View Statistics
                        </button>
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
                    Bookmarks with "{{ $tag->name }}" ({{ $bookmarks->total() }})
                </h5>
                <div class="d-flex align-items-center">
                    <!-- Search -->
                    <form method="GET" action="{{ route('tags.show', $tag) }}" class="me-3">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="search"
                                   value="{{ request('search') }}" placeholder="Search bookmarks...">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Sort -->
                    <form method="GET" action="{{ route('tags.show', $tag) }}" id="sortForm">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select class="form-select form-select-sm" name="sort" onchange="document.getElementById('sortForm').submit();" style="width: auto;">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                            <option value="updated_at" {{ request('sort') == 'updated_at' ? 'selected' : '' }}>Recently Updated</option>
                            <option value="clicks" {{ request('sort') == 'clicks' ? 'selected' : '' }}>Most Visited</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if($bookmarks->count() > 0)
                    <div class="row g-3">
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

                                    <!-- Category -->
                                    @if($bookmark->category)
                                        <div class="mb-2">
                                            <span class="badge" style="background-color: {{ $bookmark->category->color }};">
                                                <i class="bi bi-{{ $bookmark->category->icon }}"></i>
                                                {{ $bookmark->category->name }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Other Tags -->
                                    @if($bookmark->tags->count() > 1)
                                        <div class="mb-3">
                                            @foreach($bookmark->tags->where('id', '!=', $tag->id)->take(3) as $otherTag)
                                                <a href="{{ route('tags.show', $otherTag) }}"
                                                   class="badge text-decoration-none me-1"
                                                   style="background-color: {{ $otherTag->color }};">
                                                    {{ $otherTag->name }}
                                                </a>
                                            @endforeach
                                            @if($bookmark->tags->where('id', '!=', $tag->id)->count() > 3)
                                                <small class="text-muted">+{{ $bookmark->tags->where('id', '!=', $tag->id)->count() - 3 }} more</small>
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

                    <!-- Pagination -->
                    @if($bookmarks->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $bookmarks->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-bookmark text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">No bookmarks found</h5>
                        @if(request('search'))
                            <p class="text-muted">No bookmarks match your search criteria.</p>
                            <a href="{{ route('tags.show', $tag) }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-clockwise"></i> Show All Bookmarks
                            </a>
                        @else
                            <p class="text-muted">You haven't tagged any bookmarks with "{{ $tag->name }}" yet.</p>
                            <a href="{{ route('bookmarks.create', ['tag' => $tag->name]) }}"
                               class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add Bookmark with this Tag
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Tag Modal -->
<div class="modal fade" id="editTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTagForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_tag_name" class="form-label">Tag Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_tag_name" name="name"
                               placeholder="Enter tag name" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="edit_tag_color" class="form-label">Color</label>
                        <div class="d-flex align-items-center">
                            <input type="color" class="form-control form-control-color"
                                   id="edit_tag_color" name="color">
                            <input type="text" class="form-control ms-2" id="edit_tag_color_text"
                                   readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Preview</label>
                        <div class="border rounded p-3 bg-light text-center">
                            <span class="badge" id="edit_tag_preview">
                                Tag Name
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Tag</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tag Statistics Modal -->
<div class="modal fade" id="tagStatsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tag Statistics: {{ $tag->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-primary">{{ $userBookmarkCount }}</h3>
                                <p class="mb-0">Your Bookmarks</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-success">{{ $totalTagUsage }}</h3>
                                <p class="mb-0">Total Usage</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-info">{{ $tagUsagePercentage }}%</h3>
                                <p class="mb-0">Your Share</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-warning">{{ $relatedTags->count() }}</h3>
                                <p class="mb-0">Related Tags</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($relatedTags->count() > 0)
                <div class="mt-4">
                    <h6>Frequently Used Together:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($relatedTags->take(10) as $relatedTag)
                            <span class="badge" style="background-color: {{ $relatedTag->color }};">
                                {{ $relatedTag->name }} ({{ $relatedTag->bookmarks_count }})
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
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

.tag-icon {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit tag modal functionality
    const editTagNameInput = document.getElementById('edit_tag_name');
    const editTagColorInput = document.getElementById('edit_tag_color');
    const editTagColorText = document.getElementById('edit_tag_color_text');
    const editTagPreview = document.getElementById('edit_tag_preview');

    // Update edit tag preview
    function updateEditPreview() {
        const name = editTagNameInput.value || 'Tag Name';
        const color = editTagColorInput.value;

        editTagPreview.textContent = name;
        editTagPreview.style.backgroundColor = color;
        editTagColorText.value = color;
    }

    if (editTagNameInput) {
        editTagNameInput.addEventListener('input', updateEditPreview);
        editTagColorInput.addEventListener('input', updateEditPreview);
    }

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

function editTag(tagId, tagName, tagColor) {
    const editTagForm = document.getElementById('editTagForm');
    const editTagModal = new bootstrap.Modal(document.getElementById('editTagModal'));

    // Set form action
    editTagForm.action = `/tags/${tagId}`;

    // Populate form fields
    document.getElementById('edit_tag_name').value = tagName;
    document.getElementById('edit_tag_color').value = tagColor;
    document.getElementById('edit_tag_color_text').value = tagColor;

    // Update preview
    const editTagPreview = document.getElementById('edit_tag_preview');
    editTagPreview.textContent = tagName;
    editTagPreview.style.backgroundColor = tagColor;

    // Show modal
    editTagModal.show();
}

function deleteTag(tagId, tagName) {
    if (confirm(`Are you sure you want to delete the tag "${tagName}"? This will remove it from all your bookmarks.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tags/${tagId}`;

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
