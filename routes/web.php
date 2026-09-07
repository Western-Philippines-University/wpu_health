<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\UnifiedPortalController;
use App\Http\Controllers\Portal\PublicPortalController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Route::prefix('portal')->middleware('web')->group(function (): void {
    Route::any('index.php', [PublicPortalController::class, 'index'])->name('portal.home');
    Route::any('assets/{path}', [PublicPortalController::class, 'asset'])
        ->where('path', '.*')
        ->name('portal.asset');
});

Route::redirect('login', '/admin/login', 302)->name('login');

Route::get('admin', function () {
    return auth('admin')->check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('admin.login');
})->name('admin.index');

Route::middleware('guest:admin')->prefix('admin')->group(function (): void {
    Route::get('login', [LoginController::class, 'create'])->name('admin.login');
    Route::post('login', [LoginController::class, 'store'])
        ->middleware('throttle:8,1')
        ->name('admin.login.store');
});

Route::middleware('auth:admin')->prefix('admin')->group(function (): void {
    Route::get('dashboard', DashboardController::class)->name('admin.dashboard');
    Route::post('logout', LogoutController::class)->name('admin.logout');

    Route::prefix('workspace')->group(function (): void {
        Route::get('exit', function () {
            auth('admin')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('admin.login');
        })->name('admin.workspace.exit');

        Route::any('{path?}', UnifiedPortalController::class)
            ->where('path', '.*')
            ->name('admin.workspace');
    });
});
