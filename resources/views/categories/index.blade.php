@extends('layouts.app')

@section('title', 'Categories')

@section('header-actions')
<a href="{{ route('categories.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg"></i> Add Category
</a>
@endsection

@section('content')
@if($rootCategories->count() > 0)
<div class="row">
    @foreach($rootCategories as $category)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-{{ $category->icon }}" style="color: {{ $category->color }}; font-size: 1.5rem;"></i>
                        <h5 class="card-title mb-0 ms-2">{{ $category->name }}</h5>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('categories.show', $category) }}">
                                <i class="bi bi-eye"></i> View Bookmarks
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('categories.edit', $category) }}">
                                <i class="bi bi-pencil"></i> Edit
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger"
                                            onclick="return confirm('Are you sure? This will move subcategories to root level.')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="badge bg-secondary">{{ $category->bookmarks_count }} bookmarks</span>
                    @if($category->children->count() > 0)
                        <span class="badge bg-info">{{ $category->children->count() }} subcategories</span>
                    @endif
                </div>

                @if($category->children->count() > 0)
                <div class="subcategories">
                    <h6 class="text-muted mb-2">Subcategories:</h6>
                    @foreach($category->children as $subcategory)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-{{ $subcategory->icon }}" style="color: {{ $subcategory->color }}"></i>
                            <a href="{{ route('categories.show', $subcategory) }}" class="ms-2 text-decoration-none">
                                {{ $subcategory->name }}
                            </a>
                        </div>
                        <span class="badge bg-light text-dark">{{ $subcategory->bookmarks_count }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-folder-open"></i> View Bookmarks
                    </a>
                    <a href="{{ route('bookmarks.index', ['category' => $category->id]) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-list"></i> Browse
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Uncategorized bookmarks count -->
@php
    $uncategorizedCount = auth()->user()->bookmarks()->whereNull('category_id')->count();
@endphp
@if($uncategorizedCount > 0)
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i>
    You have <strong>{{ $uncategorizedCount }}</strong> uncategorized bookmarks.
    <a href="{{ route('bookmarks.index', ['category' => '']) }}" class="alert-link">View them here</a>.
</div>
@endif

@else
<div class="text-center py-5">
    <i class="bi bi-folder-plus display-1 text-muted"></i>
    <h4 class="mt-3">No categories yet</h4>
    <p class="text-muted">Organize your bookmarks by creating categories</p>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Create Your First Category
    </a>
</div>
@endif
@endsection

@push('styles')
<style>
.subcategories {
    border-left: 3px solid #dee2e6;
    padding-left: 15px;
    margin-left: 10px;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,.1);
    transition: all 0.2s ease;
}
</style>
@endpush
