@extends('layouts.app')

@section('title', 'Add Bookmark')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Bookmark</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('bookmarks.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="url" class="form-label">URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('url') is-invalid @enderror"
                               id="url" name="url" value="{{ old('url') }}"
                               placeholder="https://example.com" required>
                        @error('url')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}"
                               placeholder="Leave empty to auto-generate from URL">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">If left empty, we'll try to fetch the title from the webpage.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3"
                                  placeholder="Optional description...">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @foreach($category->children as $subcategory)
                                            <option value="{{ $subcategory->id }}"
                                                    {{ old('category_id') == $subcategory->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;↳ {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror"
                               id="tags" name="tags_input" value="{{ old('tags_input') }}"
                               placeholder="Enter tags separated by commas">
                        <input type="hidden" name="tags" id="tags_hidden">
                        @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Separate multiple tags with commas. Example: web development, javascript, tutorial</div>

                        <!-- Tag suggestions -->
                        <div class="mt-2">
                            <small class="text-muted">Popular tags:</small>
                            <div class="tag-suggestions">
                                @foreach($tags->take(10) as $tag)
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1 mb-1 tag-suggestion"
                                            data-tag="{{ $tag->name }}">{{ $tag->name }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="favorite" name="favorite" value="1"
                                       {{ old('favorite') ? 'checked' : '' }}>
                                <label class="form-check-label" for="favorite">
                                    <i class="bi bi-heart"></i> Mark as favorite
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="private" name="private" value="1"
                                       {{ old('private') ? 'checked' : '' }}>
                                <label class="form-check-label" for="private">
                                    <i class="bi bi-lock"></i> Make private
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('bookmarks.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Save Bookmark
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
    const tagsInput = document.getElementById('tags');
    const tagsHidden = document.getElementById('tags_hidden');
    const tagSuggestions = document.querySelectorAll('.tag-suggestion');

    // Handle tag input
    tagsInput.addEventListener('input', function() {
        const tags = this.value.split(',').map(tag => tag.trim()).filter(tag => tag.length > 0);
        tagsHidden.value = JSON.stringify(tags);
    });

    // Handle tag suggestions
    tagSuggestions.forEach(button => {
        button.addEventListener('click', function() {
            const tagName = this.dataset.tag;
            const currentTags = tagsInput.value ? tagsInput.value.split(',').map(t => t.trim()) : [];

            if (!currentTags.includes(tagName)) {
                currentTags.push(tagName);
                tagsInput.value = currentTags.join(', ');
                tagsHidden.value = JSON.stringify(currentTags);
            }
        });
    });

    // Auto-fetch title when URL is pasted
    const urlInput = document.getElementById('url');
    const titleInput = document.getElementById('title');

    urlInput.addEventListener('blur', function() {
        if (this.value && !titleInput.value) {
            // You could implement AJAX call here to fetch title
            // For now, we'll let the backend handle it
        }
    });
});
</script>
@endpush
