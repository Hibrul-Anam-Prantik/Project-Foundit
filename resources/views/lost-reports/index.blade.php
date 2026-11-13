@extends('layouts.app')

@section('title', 'My Lost Item Reports - FoundIT')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules/lost-reports.css') }}">
@endpush

@section('content')
<div class="lr-shell">
    @include('lost-reports.partials.navbar')

    <main class="lr-main">
        <div class="lr-header-row">
            <div class="lr-title-block">
                <h1>My Lost Item Reports</h1>
                <p>Report missing belongings and track recovery progress across campus.</p>
            </div>
            <a href="{{ route('lost-reports.create') }}" class="lr-btn lr-btn-accent">
                <i class="fas fa-plus"></i> Report Lost Item
            </a>
        </div>

        @if (session('status_message'))
            <div class="lr-alert lr-alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status_message') }}</span>
            </div>
        @endif

        <!-- Status Summary Cards -->
        <section class="lr-stats-grid" aria-label="Lost report summary">
            <div class="lr-stat-card">
                <div class="lr-stat-label">Total Reports</div>
                <div class="lr-stat-value">{{ $counts['total'] }}</div>
            </div>
            <div class="lr-stat-card searching">
                <div class="lr-stat-label">Searching</div>
                <div class="lr-stat-value">{{ $counts[\App\Models\LostReport::STATUS_SEARCHING] }}</div>
            </div>
            <div class="lr-stat-card matched">
                <div class="lr-stat-label">Matched</div>
                <div class="lr-stat-value">{{ $counts[\App\Models\LostReport::STATUS_MATCHED] }}</div>
            </div>
            <div class="lr-stat-card recovered">
                <div class="lr-stat-label">Recovered</div>
                <div class="lr-stat-value">{{ $counts[\App\Models\LostReport::STATUS_RECOVERED] }}</div>
            </div>
        </section>

        <!-- Search & Filter Bar -->
        <form method="GET" action="{{ route('lost-reports.index') }}" class="lr-filter-bar">
            <input
                type="search"
                name="search"
                value="{{ $filters['search'] ?? '' }}"
                placeholder="Search by item name, description, or location..."
            >

            <select name="category">
                <option value="all">All Categories</option>
                @foreach ($categories as $slug => $label)
                    <option value="{{ $slug }}" @selected(($filters['category'] ?? '') === $slug)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <select name="status">
                <option value="">All Statuses</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="lr-btn lr-btn-primary">
                <i class="fas fa-search"></i> Filter
            </button>

            @if (!empty($filters['search']) || !empty($filters['status']) || (!empty($filters['category']) && $filters['category'] !== 'all'))
                <a href="{{ route('lost-reports.index') }}" class="lr-btn lr-btn-outline">Reset</a>
            @endif
        </form>

        <!-- Reports Grid -->
        @if ($reports->isEmpty())
            <div class="lr-empty">
                <i class="fas fa-box-open"></i>
                <h3>No lost item reports found</h3>
                <p style="margin-bottom: 1rem;">
                    @if (!empty($filters['search']) || !empty($filters['status']) || (!empty($filters['category']) && $filters['category'] !== 'all'))
                        Try clearing your filters or search with different keywords.
                    @else
                        Submit a lost item report so campus security and finders can help locate it.
                    @endif
                </p>
                <a href="{{ route('lost-reports.create') }}" class="lr-btn lr-btn-primary">
                    <i class="fas fa-plus"></i> Create Your First Report
                </a>
            </div>
        @else
            <section class="lr-grid">
                @foreach ($reports as $report)
                    <article class="lr-card">
                        <div class="lr-card-media">
                            @if ($report->photo_url)
                                <img src="{{ $report->photo_url }}" alt="{{ $report->item_name }}">
                            @else
                                <div class="lr-card-placeholder">
                                    <i class="fas fa-image"></i>
                                    <span>No Photo Attached</span>
                                </div>
                            @endif
                        </div>

                        <div class="lr-card-body">
                            <div class="lr-card-meta-top">
                                <span class="lr-category-tag">{{ $report->categoryLabel() }}</span>
                                <span class="lr-badge {{ $report->statusBadgeClass() }}">
                                    {{ $report->statusLabel() }}
                                </span>
                            </div>

                            <h2 class="lr-card-title">{{ $report->item_name }}</h2>
                            <p class="lr-card-desc">{{ $report->description }}</p>

                            <div class="lr-card-details">
                                <div><i class="fas fa-map-marker-alt"></i> {{ $report->location }}</div>
                                <div><i class="fas fa-calendar-alt"></i> Lost on {{ $report->lost_date->format('M d, Y') }}</div>
                            </div>

                            <div class="lr-card-actions">
                                <a href="{{ route('lost-reports.show', $report) }}" class="lr-btn lr-btn-primary lr-btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('lost-reports.edit', $report) }}" class="lr-btn lr-btn-outline lr-btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form
                                    method="POST"
                                    action="{{ route('lost-reports.destroy', $report) }}"
                                    onsubmit="return confirm('Delete this lost item report? This cannot be undone.');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="lr-btn lr-btn-danger lr-btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>

            <div class="lr-pagination">
                {{ $reports->links() }}
            </div>
        @endif
    </main>
</div>
@endsection
