<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use App\Services\AdminSecurityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(private readonly AdminSecurityService $security)
    {
    }

    public function create(): View
    {
        return view('admin.auth.login');
    }

    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $username = $request->validated('username');
        $password = $request->validated('password');

        if ($this->security->isLocked($username)) {
            throw ValidationException::withMessages([
                'username' => __('Too many failed attempts. Try again later.'),
            ]);
        }

        $admin = Admin::query()->where('username', $username)->first();

        if (! $admin || ! $this->security->verifyPassword($admin, $password)) {
            $this->security->recordAttempt($username, false);
            throw ValidationException::withMessages([
                'username' => __('These credentials do not match our records.'),
            ]);
        }

        $this->security->recordAttempt($username, true);

        Auth::guard('admin')->login($admin);

        $request->session()->regenerate();

        return redirect()->intended(route('admin.workspace', ['path' => 'admin/admin.php']));
    }
}
