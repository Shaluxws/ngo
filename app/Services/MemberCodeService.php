<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Facades\DB;

class MemberCodeService
{
    /**
     * Generate a unique, race-condition safe member code.
     * Format: NSF-YYYY-000001
     */
    public static function generate(?int $year = null): string
    {
        $year = $year ?: (int) date('Y');
        $prefix = "NSF-{$year}-";

        return DB::transaction(function () use ($prefix, $year) {
            // Find highest existing sequence for this prefix even among soft-deleted records
            $latestCode = Member::withTrashed()
                ->where('member_code', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('member_code');

            $nextSequence = 1;

            if ($latestCode && preg_match('/^NSF-\d{4}-(\d{6})$/', $latestCode, $matches)) {
                $nextSequence = ((int) $matches[1]) + 1;
            } else {
                // Also check count of records for the year as fallback safety
                $count = Member::withTrashed()
                    ->where('member_code', 'like', "{$prefix}%")
                    ->count();
                $nextSequence = $count + 1;
            }

            // Ensure generated code does not already exist
            do {
                $code = $prefix . str_pad((string) $nextSequence, 6, '0', STR_PAD_LEFT);
                $exists = Member::withTrashed()->where('member_code', $code)->exists();
                if ($exists) {
                    $nextSequence++;
                }
            } while ($exists);

            return $code;
        });
    }
}
