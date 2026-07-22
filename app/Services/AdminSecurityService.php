<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class AdminSecurityService
{
    public const MAX_ATTEMPTS = 5;

    public const LOCKOUT_SECONDS = 900;

    public const ATTEMPT_WINDOW_SECONDS = 900;

    public function isLocked(string $username): bool
    {
        $admin = Admin::query()->where('username', $username)->first();
        if (! $admin || $admin->locked_until === null) {
            return false;
        }

        return $admin->locked_until->isFuture();
    }

    public function recordAttempt(string $username, bool $success, ?string $ip = null, ?string $userAgent = null): void
    {
        DB::table('admin_login_attempts')->insert([
            'username' => mb_substr($username, 0, 50),
            'ip_address' => mb_substr($ip ?? request()->ip(), 0, 45),
            'user_agent' => mb_substr($userAgent ?? (string) request()->userAgent(), 0, 255),
            'successful' => $success,
            'attempted_at' => now(),
        ]);

        $admin = Admin::query()->where('username', $username)->first();
        if (! $admin) {
            return;
        }

        if ($success) {
            $admin->forceFill(['failed_login_attempts' => 0, 'locked_until' => null])->save();

            return;
        }

        $admin->increment('failed_login_attempts');

        $recentFails = DB::table('admin_login_attempts')
            ->where('username', $username)
            ->where('successful', false)
            ->where('attempted_at', '>', now()->subSeconds(self::ATTEMPT_WINDOW_SECONDS))
            ->count();

        if ($recentFails >= self::MAX_ATTEMPTS) {
            $admin->forceFill(['locked_until' => now()->addSeconds(self::LOCKOUT_SECONDS)])->save();
        }
    }

    public function verifyPassword(Admin $admin, string $password): bool
    {
        $stored = (string) $admin->password;

        if ($stored === '') {
            return false;
        }

        if (str_starts_with($stored, '$2y$') || str_starts_with($stored, '$2a$') || str_starts_with($stored, '$argon2')) {
            if (! password_verify($password, $stored)) {
                return false;
            }
            if (password_needs_rehash($stored, PASSWORD_ARGON2ID)) {
                $admin->forceFill([
                    'password' => password_hash($password, PASSWORD_ARGON2ID),
                    'password_changed_at' => now(),
                ])->save();
            }

            return true;
        }

        // Legacy plaintext — rehash on successful verification.
        if (! hash_equals($stored, $password)) {
            return false;
        }

        $admin->forceFill([
            'password' => password_hash($password, PASSWORD_ARGON2ID),
            'password_changed_at' => now(),
        ])->save();

        return true;
    }
}
