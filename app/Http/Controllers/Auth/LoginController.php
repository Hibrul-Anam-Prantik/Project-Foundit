<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Display the FoundIT application login view.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /*
     * Note for Sprint 1:
     * Authentication, session validation, and G-Suite OAuth logic
     * will be implemented when backend feature work commences.
     */
}
