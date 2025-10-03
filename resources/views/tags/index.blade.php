@extends('layouts.app')

@section('title', 'Tags')

@section('content')
<div class="row">
    <!-- Header Section -->
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="mb-1">
                            <i class="bi bi-tags-fill text-primary"></i>
                            Tags Management
                        </h2>
                        <p class="text-muted mb-0">Organize your bookmarks with tags</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTagModal">
                            <i class="bi bi-plus-circle"></i> Create Tag
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-12 mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h3 class="mb-0">{{ $totalTags }}</h3>
                                <p class="mb-0">Total Tags</p>
                            </div>
                            <div class="ms-3">
                                <i class="bi bi-tags" style="font-size: 2.5rem; opacity: 0.7;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h3 class="mb-0">{{ $totalBookmarksWithTags }}</h3>
                                <p class="mb-0">Tagged Bookmarks</p>
                            </div>
                            <div class="ms-3">
                                <i class="bi bi-bookmark-check" style="font-size: 2.5rem; opacity: 0.7;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h3 class="mb-0">{{ $averageTagsPerBookmark }}</h3>
                                <p class="mb-0">Avg Tags/Bookmark</p>
                            </div>
                            <div class="ms-3">
                                <i class="bi bi-graph-up" style="font-size: 2.5rem; opacity: 0.7;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('tags.index') }}" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" name="search"
                                   value="{{ request('search') }}" placeholder="Search tags...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="filter">
                            <option value="">All Tags</option>
                            <option value="popular" {{ request('filter') == 'popular' ? 'selected' : '' }}>
                                Popular (5+ bookmarks)
                            </option>
                            <option value="recent" {{ request('filter') == 'recent' ? 'selected' : '' }}>
                                Recently Created
                            </option>
                            <option value="alphabetical" {{ request('filter') == 'alphabetical' ? 'selected' : '' }}>
                                Alphabetical
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                        <a href="{{ route('tags.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tags Grid -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-collection"></i>
                    Your Tags ({{ $tags->total() }})
                </h5>
            </div>
            <div class="card-body">
                @if($tags->count() > 0)
                    <div class="row g-3">
                        @foreach($tags as $tag)
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="card h-100 tag-card" style="border-left: 4px solid {{ $tag->color }};">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="tag-color-indicator me-2"
                                                 style="width: 12px; height: 12px; background-color: {{ $tag->color }}; border-radius: 50%;"></div>
                                            <h6 class="mb-0">
                                                <a href="{{ route('tags.show', $tag) }}"
                                                   class="text-decoration-none tag-name">
                                                    {{ $tag->name }}
                                                </a>
                                            </h6>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-ghost" type="button"
                                                    data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('tags.show', $tag) }}">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                </li>
                                                @can('update', $tag)
                                                <li>
                                                    <button class="dropdown-item" onclick="editTag({{ $tag->id }}, '{{ $tag->name }}', '{{ $tag->color }}')">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </button>
                                                </li>
                                                @endcan
                                                @can('delete', $tag)
                                                <li>
                                                    <button class="dropdown-item text-danger"
                                                            onclick="deleteTag({{ $tag->id }}, '{{ $tag->name }}')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="text-center mb-3">
                                        <div class="tag-usage-circle"
                                             style="background: conic-gradient({{ $tag->color }} {{ min(($userBookmarkCounts[$tag->id] ?? 0) * 10, 360) }}deg, #e9ecef 0deg);">
                                            <div class="tag-usage-number">
                                                {{ $userBookmarkCounts[$tag->id] ?? 0 }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <small class="text-muted">
                                            {{ Str::plural('bookmark', $userBookmarkCounts[$tag->id] ?? 0) }}
                                        </small>
                                    </div>

                                    <div class="mt-3 text-center">
                                        <a href="{{ route('tags.show', $tag) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            View Bookmarks
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($tags->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $tags->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-tags text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">No tags found</h5>
                        @if(request('search') || request('filter'))
                            <p class="text-muted">Try adjusting your search or filter criteria.</p>
                            <a href="{{ route('tags.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-clockwise"></i> Show All Tags
                            </a>
                        @else
                            <p class="text-muted">Start organizing your bookmarks by creating tags.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTagModal">
                                <i class="bi bi-plus-circle"></i> Create Your First Tag
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create Tag Modal -->
<div class="modal fade" id="createTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createTagForm" method="POST" action="{{ route('tags.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tag_name" class="form-label">Tag Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tag_name" name="name"
                               placeholder="Enter tag name" required maxlength="50">
                        <div class="form-text">Choose a descriptive name for your tag.</div>
                    </div>
                    <div class="mb-3">
                        <label for="tag_color" class="form-label">Color</label>
                        <div class="d-flex align-items-center">
                            <input type="color" class="form-control form-control-color"
                                   id="tag_color" name="color" value="#6c757d">
                            <input type="text" class="form-control ms-2" id="tag_color_text"
                                   value="#6c757d" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Preview</label>
                        <div class="border rounded p-3 bg-light text-center">
                            <span class="badge" id="tag_preview" style="background-color: #6c757d;">
                                Tag Name
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Tag</button>
                </div>
            </form>
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
@endsection

@push('styles')
<style>
.tag-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.tag-card:hover {
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

.tag-usage-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin: 0 auto;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tag-usage-number {
    font-size: 1.2rem;
    font-weight: bold;
    color: #495057;
}

.tag-color-indicator {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.tag-name:hover {
    text-decoration: underline !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Create tag modal functionality
    const createTagModal = document.getElementById('createTagModal');
    const tagNameInput = document.getElementById('tag_name');
    const tagColorInput = document.getElementById('tag_color');
    const tagColorText = document.getElementById('tag_color_text');
    const tagPreview = document.getElementById('tag_preview');

    // Update create tag preview
    function updateCreatePreview() {
        const name = tagNameInput.value || 'Tag Name';
        const color = tagColorInput.value;

        tagPreview.textContent = name;
        tagPreview.style.backgroundColor = color;
        tagColorText.value = color;
    }

    tagNameInput.addEventListener('input', updateCreatePreview);
    tagColorInput.addEventListener('input', updateCreatePreview);

    // Edit tag modal functionality
    const editTagModal = document.getElementById('editTagModal');
    const editTagForm = document.getElementById('editTagForm');
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

    editTagNameInput.addEventListener('input', updateEditPreview);
    editTagColorInput.addEventListener('input', updateEditPreview);
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
    if (confirm(`Are you sure you want to delete the tag "${tagName}"?`)) {
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
</script>
@endpush
