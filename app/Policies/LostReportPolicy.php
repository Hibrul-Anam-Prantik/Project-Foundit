<?php

namespace App\Policies;

use App\Models\LostReport;
use App\Models\User;

/**
 * Authorization policy for R1 (Lost Item Reports).
 *
 * Auto-discovered by Laravel via naming convention (`LostReport` -> `LostReportPolicy`),
 * avoiding edits to AuthServiceProvider.php.
 */
class LostReportPolicy
{
    /**
     * Students can view their own reports list.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'student';
    }

    /**
     * Only the student who created the report (or staff/admin) can view it.
     */
    public function view(User $user, LostReport $lostReport): bool
    {
        return $user->id === $lostReport->user_id
            || in_array($user->role, ['security', 'admin'], true);
    }

    /**
     * Only students can create lost item reports.
     */
    public function create(User $user): bool
    {
        return $user->role === 'student';
    }

    /**
     * Only the owning student can update their lost report.
     */
    public function update(User $user, LostReport $lostReport): bool
    {
        return $user->role === 'student' && $user->id === $lostReport->user_id;
    }

    /**
     * Only the owning student can delete their lost report.
     */
    public function delete(User $user, LostReport $lostReport): bool
    {
        return $user->role === 'student' && $user->id === $lostReport->user_id;
    }
}
