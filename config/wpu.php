<?php

return [
    'cache' => [
        'reference_ttl' => (int) env('WPU_REFERENCE_CACHE_TTL', 900),
        'settings_ttl' => (int) env('WPU_SETTINGS_CACHE_TTL', 120),
        'dashboard_ttl' => (int) env('WPU_DASHBOARD_CACHE_TTL', 60),
    ],

    'queue' => [
        'reports' => env('WPU_REPORTS_QUEUE', 'default'),
        'activity_logs' => env('WPU_ACTIVITY_LOGS_QUEUE', 'default'),
    ],

    'maintenance' => [
        'session_prune' => true,
        'legacy_cache_cleanup' => true,
    ],
];
