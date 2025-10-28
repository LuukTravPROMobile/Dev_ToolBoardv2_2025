<?php

namespace App\Http\Controllers;

use App\Services\CacheService;
use Illuminate\Support\Facades\Http;

class SentryController extends Controller
{
    public function getErrors()
    {
        return CacheService::getCachedData('sentry', '/errors', 900, function () {
            $response = Http::withToken(config('services.sentry.api_key'))
                ->get('https://sentry.io/api/0/organizations/travpro/issues/');
            
            return $response->json();
        });
    }
}
