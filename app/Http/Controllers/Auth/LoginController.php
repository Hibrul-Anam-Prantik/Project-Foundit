<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display the FoundIT application login view, or redirect if authenticated.
     */
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->to($this->homePathForRole($request->user()->role));
        }

        return view('auth.login');
    }

    /**
     * Authenticate a FoundIT user via university email and password.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended($this->homePathForRole($request->user()->role));
    }

    /**
     * Log the user out and invalidate their session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Resolve landing redirect by user role without coupling to unbuilt modules.
     */
    private function homePathForRole(string $role): string
    {
        return match ($role) {
            'student' => route('lost-reports.index'),
            default => route('lost-reports.index'),
        };
    }
}
