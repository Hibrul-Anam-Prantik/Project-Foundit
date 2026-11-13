@extends('layouts.app')

@section('title', 'Edit Lost Report - FoundIT')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/lost-reports.css') }}">
@endpush

@section('content')
<div class="lr-shell">
    @include('lost-reports.partials.navbar')

    <main class="lr-main" style="max-width: 860px;">
        <div class="lr-header-row">
            <div class="lr-title-block">
                <h1>Edit Lost Item Report</h1>
                <p>Update item details, photo, or recovery status for <strong>{{ $report->item_name }}</strong>.</p>
            </div>
            <a href="{{ route('lost-reports.show', $report) }}" class="lr-btn lr-btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Report
            </a>
        </div>

        @if ($errors->any())
            <div class="lr-alert lr-alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Please correct the highlighted fields below.</span>
            </div>
        @endif

        <div class="lr-panel">
            <form method="POST" action="{{ route('lost-reports.update', $report) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('lost-reports.partials.form', [
                    'report' => $report,
                    'categories' => $categories,
                    'locations' => $locations,
                    'statuses' => $statuses,
                ])

                <div class="lr-form-footer">
                    <a href="{{ route('lost-reports.show', $report) }}" class="lr-btn lr-btn-outline">Cancel</a>
                    <button type="submit" class="lr-btn lr-btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
