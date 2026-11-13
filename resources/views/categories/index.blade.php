@extends('layouts.app')

@section('title', 'FoundIT - Categories')

@push('styles')
    @include('categories._styles')
@endpush

@section('content')
    <div class="cat-page">
        <div class="cat-header">
            <div>
                <h1>Item Categories</h1>
            </div>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> New Category
            </a>
        </div>

        {{-- Flash message set by the controller after a successful create/update/delete --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($categories->isEmpty())
            <div class="empty-state">
                <i class="fa-solid fa-tags"></i>
                <p>No categories yet. Create the first one to start organising items.</p>
            </div>
        @else
            <table class="cat-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            {{-- Fallback keeps the row tidy when description was left blank --}}
                            <td>{{ $category->description ?: '—' }}</td>
                            <td>{{ $category->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="cat-actions">
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-ghost">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>

                                    {{-- A link cannot send DELETE, so a tiny form with @method('DELETE') does it --}}
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                          onsubmit="return confirm('Delete the category “{{ $category->name }}”? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
