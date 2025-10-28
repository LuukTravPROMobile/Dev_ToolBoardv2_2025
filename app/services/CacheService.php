<?php

namespace App\Services;

use App\Models\Cache;
use Illuminate\Support\Facades\Http;

class CacheService
{
    /**
     * Haal gecachte data op of ververs deze indien verlopen.
     */
    public static function getCachedData(string $source, string $endpoint, int $ttl, callable $fetchCallback)
    {
        $cache = Cache::where('source', $source)
            ->where('endpoint', $endpoint)
            ->latest('cached_at')
            ->first();

        // Controleer of cache nog geldig is
        if ($cache && now()->diffInSeconds($cache->cached_at) < $ttl) {
            return json_decode($cache->data, true);
        }

        // Anders: haal data opnieuw op via callback
        $data = $fetchCallback();

        Cache::updateOrCreate(
            ['source' => $source, 'endpoint' => $endpoint],
            [
                'data' => json_encode($data),
                'cached_at' => now(),
                'ttl' => $ttl,
            ]
        );

        return $data;
    }
}
