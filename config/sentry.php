<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sentry Configuration
    |--------------------------------------------------------------------------
    |
    | Store Sentry-related configuration here. Tokens should be provided via
    | environment variables and not committed to source control.
    |
    */

    'api_token' => env('SENTRY_API_TOKEN'),
    'org' => env('SENTRY_ORG'),
    'host' => env('SENTRY_HOST', 'https://sentry.io/api/0'),
    'web_url' => env('SENTRY_WEB_URL', 'https://sentry.io'),

    // Default cache TTL for controller that aggregates data (seconds)
    'cache_ttl' => env('SENTRY_CACHE_TTL', 900), // 15 minutes
];
