<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.login');
    }

    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $admin = Admin::query()->where('username', $request->validated('username'))->first();

        $password = $request->validated('password');
        $valid = $admin && (
            Hash::check($password, $admin->password)
            || hash_equals((string) $admin->password, $password)
        );

        if (! $valid) {
            throw ValidationException::withMessages([
                'username' => __('These credentials do not match our records.'),
            ]);
        }

        Auth::guard('admin')->login($admin);

        $request->session()->regenerate();

        return redirect()->intended(route('admin.workspace', ['path' => 'admin/admin.php']));
    }
}
