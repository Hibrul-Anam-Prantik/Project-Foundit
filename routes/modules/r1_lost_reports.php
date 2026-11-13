<?php

use App\Http\Controllers\LostReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Module R1: Lost Item Reports (Actor: Student)
|--------------------------------------------------------------------------
|
| Isolated route group for R1. Teammates should create separate files in
| routes/modules/ (e.g., r6_found_items.php) to avoid Git merge conflicts.
|
*/

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::resource('lost-reports', LostReportController::class);
    Route::patch('lost-reports/{lost_report}/status', [LostReportController::class, 'updateStatus'])
        ->name('lost-reports.update-status');
});
