<?php

namespace App\Traits;

use App\Models\StaffActivityLog;

trait LogsActivity
{
    /**
     * Log a staff activity.
     *
     * @param  string       $activityName   Human-readable label, e.g. "Created Beneficiary"
     * @param  string       $module   Category, e.g. "Beneficiary", "Event", "Attendance"
     * @param  string       $description    One-liner description
     * @param  array|string $details        Any extra data (will be JSON-encoded if array)
     * @return void
     */
    protected function logActivity(
        string $activityName,
        string $module,
        string $description = '',
        mixed  $details = null
    ): void {
        StaffActivityLog::create([
            'user_id'          => auth()->id() ?? 1,
            'activity_name'    => $activityName,
            'module'           => $module,
            'description'      => $description,
            'timestamp'        => now(),
            'activity_details' => is_array($details) ? json_encode($details) : $details,
        ]);
    }
}