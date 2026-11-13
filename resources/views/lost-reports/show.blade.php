@extends('layouts.app')

@section('title', $report->item_name . ' - Lost Report - FoundIT')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/lost-reports.css') }}">
@endpush

@section('content')
<div class="lr-shell">
    @include('lost-reports.partials.navbar')

    <main class="lr-main" style="max-width: 1050px;">
        <div class="lr-header-row">
            <div class="lr-title-block">
                <h1>{{ $report->item_name }}</h1>
                <p>Report #{{ $report->id }} &bull; Submitted {{ $report->created_at->diffForHumans() }}</p>
            </div>
            <div style="display:flex; gap:0.6rem; flex-wrap:wrap;">
                <a href="{{ route('lost-reports.index') }}" class="lr-btn lr-btn-outline">
                    <i class="fas fa-arrow-left"></i> All Reports
                </a>
                @can('update', $report)
                    <a href="{{ route('lost-reports.edit', $report) }}" class="lr-btn lr-btn-primary">
                        <i class="fas fa-edit"></i> Edit Report
                    </a>
                @endcan
                @can('delete', $report)
                    <form
                        method="POST"
                        action="{{ route('lost-reports.destroy', $report) }}"
                        onsubmit="return confirm('Delete this lost item report permanently?');"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="lr-btn lr-btn-danger">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        @if (session('status_message'))
            <div class="lr-alert lr-alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status_message') }}</span>
            </div>
        @endif

        <div class="lr-panel">
            <div class="lr-detail-grid">
                <div class="lr-detail-photo">
                    @if ($report->photo_url)
                        <img src="{{ $report->photo_url }}" alt="{{ $report->item_name }}">
                    @else
                        <div class="lr-card-placeholder">
                            <i class="fas fa-image"></i>
                            <span>No photo uploaded</span>
                        </div>
                    @endif
                </div>

                <div>
                    <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.85rem;">
                        <span class="lr-category-tag">{{ $report->categoryLabel() }}</span>
                        <span class="lr-badge {{ $report->statusBadgeClass() }}">
                            {{ $report->statusLabel() }}
                        </span>
                    </div>

                    <h2 style="font-size:1.25rem; margin-bottom:0.5rem; color:#111827;">Description</h2>
                    <p style="color:#374151; line-height:1.6; white-space:pre-line;">{{ $report->description }}</p>

                    <div class="lr-meta-list">
                        <div class="lr-meta-item">
                            <span>Date Lost</span>
                            <strong>{{ $report->lost_date->format('F d, Y') }}</strong>
                        </div>
                        <div class="lr-meta-item">
                            <span>Last Seen Location</span>
                            <strong>{{ $report->location }}</strong>
                        </div>
                        <div class="lr-meta-item">
                            <span>Reported By</span>
                            <strong>{{ $report->user->name }}</strong>
                        </div>
                        <div class="lr-meta-item">
                            <span>Last Updated</span>
                            <strong>{{ $report->updated_at->format('M d, Y h:i A') }}</strong>
                        </div>
                    </div>

                    <!-- Status Lifecycle Tracker -->
                    <h3 style="font-size:1rem; font-weight:700; color:#111827; margin-bottom:0.5rem;">
                        Recovery Status Tracker
                    </h3>
                    @php
                        $order = ['searching' => 1, 'matched' => 2, 'recovered' => 3];
                        $currentStep = $order[$report->status] ?? 1;
                    @endphp
                    <div class="lr-tracker">
                        <div class="lr-step {{ $currentStep === 1 ? 'is-active' : ($currentStep > 1 ? 'is-done' : '') }}">
                            <i class="fas fa-search"></i> 1. Searching
                        </div>
                        <div class="lr-step {{ $currentStep === 2 ? 'is-active' : ($currentStep > 2 ? 'is-done' : '') }}">
                            <i class="fas fa-link"></i> 2. Matched
                        </div>
                        <div class="lr-step {{ $currentStep === 3 ? 'is-done' : '' }}">
                            <i class="fas fa-check-double"></i> 3. Recovered
                        </div>
                    </div>

                    @can('update', $report)
                        <div class="lr-status-actions">
                            @foreach ($statuses as $statusKey => $statusLabel)
                                @if ($report->status !== $statusKey)
                                    <form method="POST" action="{{ route('lost-reports.update-status', $report) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $statusKey }}">
                                        <button type="submit" class="lr-btn lr-btn-outline lr-btn-sm">
                                            Mark as {{ $statusLabel }}
                                        </button>
                                    </form>
                                @endif
                            @endforeach
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
