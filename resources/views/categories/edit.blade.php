@extends('layouts.app')

@section('title', 'FoundIT - Edit Category')

@push('styles')
    @include('categories._styles')
@endpush

@section('content')
    <div class="cat-page">
        <div class="cat-header">
            <div>
                <h1>Edit Category</h1>
                <p>Update the name or description. The name must stay unique.</p>
            </div>
        </div>

        @include('categories._form', ['category' => $category])
    </div>
@endsection
