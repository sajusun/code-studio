<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    use ApiResponse;

    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();

            if ($request->wantsJson()) {
                return self::success('Login successful', ['user' => $user]);
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Welcome back, {$user->name}!");
        }

        if ($request->wantsJson()) {
            return self::error('Invalid email or password credentials.', 401);
        }

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return self::success('Logged out successfully');
        }

        return redirect()->route('admin.login')
            ->with('success', 'Logged out successfully.');
    }
}
