<?php

namespace App\Services;

use App\Models\VolunteerProfile;
use Illuminate\Support\Facades\DB;

class VolunteerCodeService
{
    /**
     * Generate a unique, race-condition safe volunteer code.
     * Format: VOL-YYYY-000001
     */
    public static function generate(?int $year = null): string
    {
        $year = $year ?: (int) date('Y');
        $prefix = "VOL-{$year}-";

        return DB::transaction(function () use ($prefix, $year) {
            // Find highest existing sequence for this prefix even among soft-deleted records
            $latestCode = VolunteerProfile::withTrashed()
                ->where('volunteer_code', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('volunteer_code');

            $nextSequence = 1;

            if ($latestCode && preg_match('/^VOL-\d{4}-(\d{6})$/', $latestCode, $matches)) {
                $nextSequence = ((int) $matches[1]) + 1;
            } else {
                $count = VolunteerProfile::withTrashed()
                    ->where('volunteer_code', 'like', "{$prefix}%")
                    ->count();
                $nextSequence = $count + 1;
            }

            // Ensure generated code does not already exist
            do {
                $code = $prefix . str_pad((string) $nextSequence, 6, '0', STR_PAD_LEFT);
                $exists = VolunteerProfile::withTrashed()->where('volunteer_code', $code)->exists();
                if ($exists) {
                    $nextSequence++;
                }
            } while ($exists);

            return $code;
        });
    }
}
