<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheInvalidationService
{
    public function flushDashboardStats(): void
    {
        app(DashboardStatsService::class)->forget();
    }

    public function flushAll(): void
    {
        $this->flushDashboardStats();
        Cache::flush();
    }
}
