@extends('layouts.app')

@section('title', 'Foundit - Lost & Found Portal')

@section('content')
    <!-- =================================== -->
    <!--          LOGIN PAGE (Default)       -->
    <!-- =================================== -->
    <div id="login-page">
        <div class="login-container">
            <div class="login-logo">
                <h1>Found🔎t </h1>
            </div>
            <h2>University Lost & Found Portal</h2>
            <p>Please log in using your official university account.</p>

            @if ($errors->any())
                <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; border-radius:6px; padding:0.75rem 1rem; margin-bottom:1rem; text-align:left; font-size:0.9rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- --- 1. Email and Password Form --- -->
            <form id="email-login-form" method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="input-group">
                    <label for="email-input">University Email</label>
                    <input 
                        type="email" 
                        id="email-input" 
                        name="email" 
                        placeholder="e.g., student.name@g.bracu.ac.bd" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email" 
                        autofocus
                    >
                </div>
                <div class="input-group">
                    <label for="password-input">Password</label>
                    <input 
                        type="password" 
                        id="password-input" 
                        name="password" 
                        placeholder="********" 
                        required 
                        autocomplete="current-password"
                    >
                </div>
                <!-- Submit button uses the standard .submit-btn style -->
                <button type="submit" class="submit-btn" style="width: 100%; margin-bottom: 1.5rem;">Log In</button>
                <p class="divider-text">OR</p>
            </form>

            <!-- --- 2. G-Suite Sign-in (Main Method) --- -->
            <button id="gsuite-login-btn" class="g-suite-btn" type="button">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48">
                    <path fill="#EA4335"
                        d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z">
                    </path>
                    <path fill="#4285F4"
                        d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z">
                    </path>
                    <path fill="#FBBC05"
                        d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z">
                    </path>
                    <path fill="#34A853"
                        d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z">
                    </path>
                    <path fill="none" d="M0 0h48v48H0z"></path>
                </svg>
                Sign in with G-Suite
            </button>
        </div>
    </div>
@endsection
