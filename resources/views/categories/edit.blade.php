@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Category: {{ $category->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('categories.update', $category) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $category->name) }}"
                               placeholder="Enter category name" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Category</label>
                        <select class="form-select @error('parent_id') is-invalid @enderror"
                                id="parent_id" name="parent_id">
                            <option value="">None (Root Category)</option>
                            @foreach($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}"
                                        {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                    {{ $parentCategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Select a parent category to create a subcategory.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="color" class="form-label">Color <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center">
                                    <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror"
                                           id="color" name="color" value="{{ old('color', $category->color) }}" required>
                                    <input type="text" class="form-control ms-2" id="colorText"
                                           value="{{ old('color', $category->color) }}" readonly>
                                </div>
                                @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon <span class="text-danger">*</span></label>
                                <select class="form-select @error('icon') is-invalid @enderror"
                                        id="icon" name="icon" required>
                                    <option value="">Select an icon</option>
                                    <option value="folder" {{ old('icon', $category->icon) == 'folder' ? 'selected' : '' }}>📁 Folder</option>
                                    <option value="folder-fill" {{ old('icon', $category->icon) == 'folder-fill' ? 'selected' : '' }}>📂 Folder Fill</option>
                                    <option value="code-slash" {{ old('icon', $category->icon) == 'code-slash' ? 'selected' : '' }}>💻 Development</option>
                                    <option value="newspaper" {{ old('icon', $category->icon) == 'newspaper' ? 'selected' : '' }}>📰 News</option>
                                    <option value="book" {{ old('icon', $category->icon) == 'book' ? 'selected' : '' }}>📚 Books</option>
                                    <option value="music-note" {{ old('icon', $category->icon) == 'music-note' ? 'selected' : '' }}>🎵 Music</option>
                                    <option value="film" {{ old('icon', $category->icon) == 'film' ? 'selected' : '' }}>🎬 Movies</option>
                                    <option value="camera" {{ old('icon', $category->icon) == 'camera' ? 'selected' : '' }}>📷 Photos</option>
                                    <option value="tools" {{ old('icon', $category->icon) == 'tools' ? 'selected' : '' }}>🔧 Tools</option>
                                    <option value="heart" {{ old('icon', $category->icon) == 'heart' ? 'selected' : '' }}>❤️ Favorites</option>
                                    <option value="star" {{ old('icon', $category->icon) == 'star' ? 'selected' : '' }}>⭐ Important</option>
                                    <option value="house" {{ old('icon', $category->icon) == 'house' ? 'selected' : '' }}>🏠 Home</option>
                                    <option value="briefcase" {{ old('icon', $category->icon) == 'briefcase' ? 'selected' : '' }}>💼 Work</option>
                                    <option value="cart" {{ old('icon', $category->icon) == 'cart' ? 'selected' : '' }}>🛒 Shopping</option>
                                    <option value="controller" {{ old('icon', $category->icon) == 'controller' ? 'selected' : '' }}>🎮 Gaming</option>
                                    <option value="trophy" {{ old('icon', $category->icon) == 'trophy' ? 'selected' : '' }}>🏆 Achievements</option>
                                </select>
                                @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Current Category Info -->
                    <div class="mb-4">
                        <label class="form-label">Current Category</label>
                        <div class="border rounded p-3 bg-light">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-{{ $category->icon }}" style="color: {{ $category->color }}; font-size: 1.5rem;"></i>
                                <span class="ms-2 fw-bold">{{ $category->name }}</span>
                                @if($category->parent)
                                    <span class="ms-2 text-muted">
                                        (under: {{ $category->parent->name }})
                                    </span>
                                @endif
                            </div>
                            @if($category->children_count > 0)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i>
                                        This category has {{ $category->children_count }} subcategory(ies)
                                    </small>
                                </div>
                            @endif
                            @if($category->bookmarks_count > 0)
                                <div class="mt-1">
                                    <small class="text-muted">
                                        <i class="bi bi-bookmark"></i>
                                        Contains {{ $category->bookmarks_count }} bookmark(s)
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="mb-4">
                        <label class="form-label">Preview</label>
                        <div class="border rounded p-3 bg-light">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-{{ $category->icon }}" id="iconPreview" style="color: {{ $category->color }}; font-size: 1.5rem;"></i>
                                <span class="ms-2 fw-bold" id="namePreview">{{ $category->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-info">
                                <i class="bi bi-eye"></i> View Category
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Update Category
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($category->children_count > 0 || $category->bookmarks_count > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">⚠️ Important Notice</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <strong>This category is not empty:</strong>
                    <ul class="mb-0 mt-2">
                        @if($category->children_count > 0)
                            <li>Contains {{ $category->children_count }} subcategory(ies)</li>
                        @endif
                        @if($category->bookmarks_count > 0)
                            <li>Contains {{ $category->bookmarks_count }} bookmark(s)</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const colorInput = document.getElementById('color');
    const colorText = document.getElementById('colorText');
    const iconSelect = document.getElementById('icon');
    const namePreview = document.getElementById('namePreview');
    const iconPreview = document.getElementById('iconPreview');

    // Set initial color text
    colorText.value = colorInput.value;

    // Update preview on input changes
    nameInput.addEventListener('input', updatePreview);
    colorInput.addEventListener('input', function() {
        colorText.value = this.value;
        updatePreview();
    });
    iconSelect.addEventListener('change', updatePreview);

    function updatePreview() {
        const name = nameInput.value || 'Category Name';
        const color = colorInput.value;
        const icon = iconSelect.value || 'folder';

        namePreview.textContent = name;
        iconPreview.className = `bi bi-${icon}`;
        iconPreview.style.color = color;
    }
});
</script>
@endpush
