<?php

return [
    // Custom application-specific Sentry settings used by controllers/services
    'api_token' => env('SENTRY_API_TOKEN'),
    'org' => env('SENTRY_ORG'),
    'host' => env('SENTRY_HOST', 'https://sentry.io/api/0'),
    'web_url' => env('SENTRY_WEB_URL', 'https://sentry.io'),
    'cache_ttl' => env('SENTRY_CACHE_TTL', 900), // 15 minutes
];