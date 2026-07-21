<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardStatsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(DashboardStatsService $statsService): View
    {
        return view('admin.dashboard', [
            'admin' => auth('admin')->user(),
            'stats' => $statsService->getStats(),
        ]);
    }
}
