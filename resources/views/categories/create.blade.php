@extends('layouts.app')

@section('title', 'Create Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Create New Category</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}"
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
                            @foreach($parentCategories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
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
                                           id="color" name="color" value="{{ old('color', '#007bff') }}" required>
                                    <input type="text" class="form-control ms-2" id="colorText"
                                           value="{{ old('color', '#007bff') }}" readonly>
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
                                    <option value="folder" {{ old('icon') == 'folder' ? 'selected' : '' }}>📁 Folder</option>
                                    <option value="folder-fill" {{ old('icon') == 'folder-fill' ? 'selected' : '' }}>📂 Folder Fill</option>
                                    <option value="code-slash" {{ old('icon') == 'code-slash' ? 'selected' : '' }}>💻 Development</option>
                                    <option value="newspaper" {{ old('icon') == 'newspaper' ? 'selected' : '' }}>📰 News</option>
                                    <option value="book" {{ old('icon') == 'book' ? 'selected' : '' }}>📚 Books</option>
                                    <option value="music-note" {{ old('icon') == 'music-note' ? 'selected' : '' }}>🎵 Music</option>
                                    <option value="film" {{ old('icon') == 'film' ? 'selected' : '' }}>🎬 Movies</option>
                                    <option value="camera" {{ old('icon') == 'camera' ? 'selected' : '' }}>📷 Photos</option>
                                    <option value="tools" {{ old('icon') == 'tools' ? 'selected' : '' }}>🔧 Tools</option>
                                    <option value="heart" {{ old('icon') == 'heart' ? 'selected' : '' }}>❤️ Favorites</option>
                                    <option value="star" {{ old('icon') == 'star' ? 'selected' : '' }}>⭐ Important</option>
                                    <option value="house" {{ old('icon') == 'house' ? 'selected' : '' }}>🏠 Home</option>
                                    <option value="briefcase" {{ old('icon') == 'briefcase' ? 'selected' : '' }}>💼 Work</option>
                                    <option value="cart" {{ old('icon') == 'cart' ? 'selected' : '' }}>🛒 Shopping</option>
                                    <option value="controller" {{ old('icon') == 'controller' ? 'selected' : '' }}>🎮 Gaming</option>
                                    <option value="trophy" {{ old('icon') == 'trophy' ? 'selected' : '' }}>🏆 Achievements</option>
                                </select>
                                @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="mb-4">
                        <label class="form-label">Preview</label>
                        <div class="border rounded p-3 bg-light">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-folder" id="iconPreview" style="color: #007bff; font-size: 1.5rem;"></i>
                                <span class="ms-2 fw-bold" id="namePreview">Category Name</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
