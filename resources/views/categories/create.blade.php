@extends('layouts.app')

@section('title', 'FoundIT - New Category')

@push('styles')
    @include('categories._styles')
@endpush

@section('content')
    <div class="cat-page">
        <div class="cat-header">
            <div>
                <h1>New Category</h1>
                <p>Add a category such as “Electronics”, “ID Cards” or “Bags”.</p>
            </div>
        </div>

        @include('categories._form')
    </div>
@endsection
