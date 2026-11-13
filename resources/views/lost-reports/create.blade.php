@extends('layouts.app')

@section('title', 'Report Lost Item - FoundIT')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/lost-reports.css') }}">
@endpush

@section('content')
<div class="lr-shell">
    @include('lost-reports.partials.navbar')

    <main class="lr-main" style="max-width: 860px;">
        <div class="lr-header-row">
            <div class="lr-title-block">
                <h1>Report a Lost Item</h1>
                <p>Provide accurate details and an optional photo to help identify your item.</p>
            </div>
            <a href="{{ route('lost-reports.index') }}" class="lr-btn lr-btn-outline">
                <i class="fas fa-arrow-left"></i> Back to My Reports
            </a>
        </div>

        @if ($errors->any())
            <div class="lr-alert lr-alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Please correct the highlighted fields below.</span>
            </div>
        @endif

        <div class="lr-panel">
            <form method="POST" action="{{ route('lost-reports.store') }}" enctype="multipart/form-data">
                @csrf

                @include('lost-reports.partials.form', [
                    'categories' => $categories,
                    'locations' => $locations,
                ])

                <div class="lr-form-footer">
                    <a href="{{ route('lost-reports.index') }}" class="lr-btn lr-btn-outline">Cancel</a>
                    <button type="submit" class="lr-btn lr-btn-accent">
                        <i class="fas fa-paper-plane"></i> Submit Lost Report
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
