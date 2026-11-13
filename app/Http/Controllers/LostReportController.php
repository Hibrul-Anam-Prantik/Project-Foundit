<?php

namespace App\Http\Controllers;

use App\Http\Requests\LostReport\StoreLostReportRequest;
use App\Http\Requests\LostReport\UpdateLostReportRequest;
use App\Models\LostReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * R1: Lost Item Report Controller.
 *
 * Handles full CRUD lifecycle and status tracking (searching, matched, recovered)
 * for lost item reports submitted by authenticated students.
 */
class LostReportController extends Controller
{
    /**
     * Display the authenticated student's lost item reports with filters and counts.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', LostReport::class);

        $userId = (int) $request->user()->id;
        $filters = $request->only(['search', 'status', 'category']);

        $reports = LostReport::query()
            ->forUser($userId)
            ->filter($filters)
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $statusTotals = LostReport::query()
            ->forUser($userId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $counts = [
            'total' => (int) $statusTotals->sum(),
            LostReport::STATUS_SEARCHING => (int) ($statusTotals[LostReport::STATUS_SEARCHING] ?? 0),
            LostReport::STATUS_MATCHED => (int) ($statusTotals[LostReport::STATUS_MATCHED] ?? 0),
            LostReport::STATUS_RECOVERED => (int) ($statusTotals[LostReport::STATUS_RECOVERED] ?? 0),
        ];

        return view('lost-reports.index', [
            'reports' => $reports,
            'counts' => $counts,
            'categories' => LostReport::availableCategories(),
            'statuses' => LostReport::STATUSES,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new lost item report.
     */
    public function create(): View
    {
        $this->authorize('create', LostReport::class);

        return view('lost-reports.create', [
            'categories' => LostReport::availableCategories(),
            'locations' => LostReport::availableLocations(),
        ]);
    }

    /**
     * Store a newly created lost item report in MySQL.
     */
    public function store(StoreLostReportRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $photoPath = $this->syncPhoto($request->file('photo'));

        $report = LostReport::create([
            'user_id' => $request->user()->id,
            'item_name' => $validated['item_name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'lost_date' => $validated['lost_date'],
            'location' => $validated['location'],
            'photo_path' => $photoPath,
            'status' => LostReport::STATUS_SEARCHING,
        ]);

        return redirect()
            ->route('lost-reports.show', $report)
            ->with('status_message', 'Lost item report submitted. Status is set to Searching.');
    }

    /**
     * Display a specific lost item report.
     */
    public function show(LostReport $lostReport): View
    {
        $this->authorize('view', $lostReport);

        return view('lost-reports.show', [
            'report' => $lostReport,
            'statuses' => LostReport::STATUSES,
        ]);
    }

    /**
     * Show the form for editing an existing lost item report.
     */
    public function edit(LostReport $lostReport): View
    {
        $this->authorize('update', $lostReport);

        return view('lost-reports.edit', [
            'report' => $lostReport,
            'categories' => LostReport::availableCategories(),
            'locations' => LostReport::availableLocations(),
            'statuses' => LostReport::STATUSES,
        ]);
    }

    /**
     * Update an existing lost item report in MySQL.
     */
    public function update(UpdateLostReportRequest $request, LostReport $lostReport): RedirectResponse
    {
        $validated = $request->validated();

        $photoPath = $this->syncPhoto(
            $request->file('photo'),
            $lostReport->photo_path,
            (bool) ($validated['remove_photo'] ?? false)
        );

        $lostReport->update([
            'item_name' => $validated['item_name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'lost_date' => $validated['lost_date'],
            'location' => $validated['location'],
            'status' => $validated['status'],
            'photo_path' => $photoPath,
        ]);

        return redirect()
            ->route('lost-reports.show', $lostReport)
            ->with('status_message', 'Lost item report updated successfully.');
    }

    /**
     * Quick-update the tracking status (searching, matched, recovered) from show/index views.
     */
    public function updateStatus(Request $request, LostReport $lostReport): RedirectResponse
    {
        $this->authorize('update', $lostReport);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(array_keys(LostReport::STATUSES))],
        ]);

        $lostReport->update(['status' => $validated['status']]);

        return back()->with(
            'status_message',
            'Report status updated to ' . $lostReport->statusLabel() . '.'
        );
    }

    /**
     * Permanently delete a lost item report and its uploaded photo.
     */
    public function destroy(LostReport $lostReport): RedirectResponse
    {
        $this->authorize('delete', $lostReport);

        $this->deletePhotoIfPresent($lostReport->photo_path);
        $lostReport->delete();

        return redirect()
            ->route('lost-reports.index')
            ->with('status_message', 'Lost item report deleted.');
    }

    /**
     * Handle photo replacement or removal on the public storage disk.
     */
    private function syncPhoto(
        ?UploadedFile $newPhoto,
        ?string $existingPath = null,
        bool $removeExisting = false
    ): ?string {
        if ($newPhoto !== null) {
            $this->deletePhotoIfPresent($existingPath);

            return $newPhoto->store('lost-reports', 'public');
        }

        if ($removeExisting) {
            $this->deletePhotoIfPresent($existingPath);

            return null;
        }

        return $existingPath;
    }

    /**
     * Delete a stored photo file if it exists on the public disk.
     */
    private function deletePhotoIfPresent(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
