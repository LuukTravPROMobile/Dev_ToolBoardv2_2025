<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SentryController extends Controller
{
    /**
     * Fetch aggregated error data from Sentry (organization-scoped).
     * Cached for 15 minutes.
     *
     * Expected env:
     * - SENTRY_API_TOKEN (personal or organization scoped token)
     * - SENTRY_ORG (organization slug)
     * - SENTRY_HOST (optional, default: https://sentry.io/api/0)
     */
    public function errors(Request $request)
    {
        $org = env('SENTRY_ORG');
        $token = env('SENTRY_API_TOKEN');
        $host = env('SENTRY_HOST', 'https://sentry.io/api/0');
        $webBase = env('SENTRY_WEB_URL', 'https://sentry.io');

        if (!$org || !$token) {
            return response()->json(['message' => 'Sentry configuration missing (SENTRY_ORG or SENTRY_API_TOKEN)'], 400);
        }

        $cacheKey = 'sentry.errors.' . $org;

        $data = Cache::remember($cacheKey, 15 * 60, function () use ($host, $org, $token, $webBase) {
            $headers = [
                'Authorization' => "Bearer {$token}",
                'Accept' => 'application/json',
            ];

            // 1) Get projects in the organization
            $projectsResp = Http::withHeaders($headers)->get("{$host}/organizations/{$org}/projects/");
            if ($projectsResp->failed()) {
                return ['error' => 'Failed to fetch projects from Sentry', 'status' => $projectsResp->status()];
            }

            $projects = $projectsResp->json();

            $totals = [
                'today' => 0,
                'week' => 0,
                'month' => 0,
            ];

            $perProject = [];
            $allIssues = [];

            // Helper to fetch issues for a project and period
            $fetchIssues = function ($projectSlug, $period) use ($host, $org, $headers) {
                // statsPeriod can be: 24h, 7d, 30d in Sentry API typically as '24h' etc.
                // Use issues endpoint with statsPeriod param
                $url = "{$host}/projects/{$org}/{$projectSlug}/issues/?statsPeriod={$period}&limit=100";
                $resp = Http::withHeaders($headers)->get($url);

                if ($resp->failed()) {
                    return null;
                }

                return $resp->json();
            };

            foreach ($projects as $project) {
                $slug = $project['slug'];

                // Fetch issues for today (24h), week (7d) and month (30d)
                $issuesToday = $fetchIssues($slug, '24h') ?? [];
                $issuesWeek = $fetchIssues($slug, '7d') ?? [];
                $issuesMonth = $fetchIssues($slug, '30d') ?? [];

                // Sum counts (best-effort, depending on fields returned by Sentry)
                $sumCount = function ($issues) {
                    $sum = 0;
                    foreach ($issues as $issue) {
                        if (isset($issue['count'])) {
                            $sum += (int) $issue['count'];
                        } elseif (isset($issue['stats']) && is_array($issue['stats'])) {
                            // stats is array of [ [ts, count], ... ] for groups; sum last values
                            foreach ($issue['stats'] as $series) {
                                if (is_array($series) && count($series) > 0) {
                                    // series looks like [[ts, count], ...]
                                    $last = end($series);
                                    $sum += (int) ($last[1] ?? 0);
                                }
                            }
                        }
                    }
                    return $sum;
                };

                $countToday = $sumCount($issuesToday);
                $countWeek = $sumCount($issuesWeek);
                $countMonth = $sumCount($issuesMonth);

                $totals['today'] += $countToday;
                $totals['week'] += $countWeek;
                $totals['month'] += $countMonth;

                $perProject[] = [
                    'id' => $project['id'] ?? null,
                    'slug' => $slug,
                    'name' => $project['name'] ?? $slug,
                    'today' => $countToday,
                    'week' => $countWeek,
                    'month' => $countMonth,
                ];

                // Merge issues into allIssues for global ranking
                foreach ($issuesMonth as $issue) {
                    $issue['_project'] = $slug;
                    $allIssues[] = $issue;
                }
            }

            // Determine most recent error (by lastSeen)
            $mostRecent = null;
            usort($allIssues, function ($a, $b) {
                $aTime = strtotime($a['lastSeen'] ?? ($a['dateCreated'] ?? '1970-01-01'));
                $bTime = strtotime($b['lastSeen'] ?? ($b['dateCreated'] ?? '1970-01-01'));
                return $bTime <=> $aTime;
            });

            if (!empty($allIssues)) {
                $first = $allIssues[0];
                $mostRecent = [
                    'title' => $first['title'] ?? ($first['culprit'] ?? 'Unnamed issue'),
                    'project' => $first['_project'] ?? null,
                    'lastSeen' => $first['lastSeen'] ?? null,
                    'count' => $first['count'] ?? null,
                    'url' => isset($first['id']) ? rtrim($webBase, '/') . "/organizations/{$org}/issues/{$first['id']}/?project=" . urlencode($first['_project'] ?? '') : null,
                ];
            }

            // Most common errors (by count) - take top 10
            usort($allIssues, function ($a, $b) {
                $ac = isset($a['count']) ? (int) $a['count'] : 0;
                $bc = isset($b['count']) ? (int) $b['count'] : 0;
                return $bc <=> $ac;
            });

            $common = [];
            foreach (array_slice($allIssues, 0, 10) as $issue) {
                $common[] = [
                    'title' => $issue['title'] ?? ($issue['culprit'] ?? 'Unnamed issue'),
                    'project' => $issue['_project'] ?? null,
                    'count' => $issue['count'] ?? null,
                    'lastSeen' => $issue['lastSeen'] ?? null,
                    'url' => isset($issue['id']) ? rtrim($webBase, '/') . "/organizations/{$org}/issues/{$issue['id']}/?project=" . urlencode($issue['_project'] ?? '') : null,
                ];
            }

            return [
                'totals' => $totals,
                'most_recent' => $mostRecent,
                'most_common' => $common,
                'per_project' => $perProject,
                'generated_at' => now()->toDateTimeString(),
            ];
        });

        if (isset($data['error'])) {
            return response()->json($data, 502);
        }

        return response()->json($data);
    }
}
