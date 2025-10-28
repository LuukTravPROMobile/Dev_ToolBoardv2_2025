<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class AnalyticsController extends Controller
{
    /**
     * Return graduates analytics.
     *
     * GET /api/v1/analytics/graduates?role=graduate
     *
     * Response structure:
     * {
     *   totals: { total, today, week, month },
     *   series: [{ period: '2025-01', count: 12 }, ...],
     *   generated_at: ...,
     *   meta: { used_role_filter: 'graduate', role_column_exists: true }
     * }
     */
    public function graduates(Request $request)
    {
        $role = $request->query('role', 'graduate');

        // Check if role column exists in users table
        $roleColumnExists = \Schema::hasColumn('users', 'role');

        $baseQuery = User::query();
        if ($roleColumnExists) {
            $baseQuery->where('role', $role);
        } else {
            // no role column - don't filter but indicate in meta
        }

        // totals
        $total = (clone $baseQuery)->count();

        // helper periods
        $today = (clone $baseQuery)->whereDate('created_at', now()->toDateString())->count();
        $weekStart = now()->subDays(7)->startOfDay();
        $week = (clone $baseQuery)->where('created_at', '>=', $weekStart)->count();
        $monthStart = now()->subDays(30)->startOfDay();
        $month = (clone $baseQuery)->where('created_at', '>=', $monthStart)->count();

        // series: counts per month for last 12 months
        $series = [];
        $now = now();
        for ($i = 11; $i >= 0; $i--) {
            $start = $now->copy()->subMonths($i)->startOfMonth();
            $end = $now->copy()->subMonths($i)->endOfMonth();

            $count = (clone $baseQuery)->whereBetween('created_at', [$start, $end])->count();

            $series[] = [
                'period' => $start->format('Y-m'),
                'count' => $count,
            ];
        }

        return response()->json([
            'totals' => [
                'total' => $total,
                'today' => $today,
                'week' => $week,
                'month' => $month,
            ],
            'series' => $series,
            'generated_at' => now()->toDateTimeString(),
            'meta' => [
                'used_role_filter' => $roleColumnExists ? $role : null,
                'role_column_exists' => $roleColumnExists,
            ],
        ]);
    }
}
