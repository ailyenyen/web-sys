@extends('layouts.app')

@section('title', 'Sign Up - Resume System')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; background-color: #f8f9fa;">
    <div style="background-color: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); width: 100%; max-width: 450px; border: 1px solid #e9ecef;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #2c3e50; font-size: 1.8em; margin-bottom: 8px; font-weight: 600;">Create Account</h1>
            <p style="color: #6c757d; font-size: 0.95em;">Sign up to access the resume system</p>
        </div>

        @if($errors->any())
            <div style="padding: 12px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: 500; line-height: 1.4; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('signup') }}">
            @csrf

            <div style="margin-bottom: 20px;">
                <label for="full_name" style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; font-size: 0.95em;">Full Name</label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    value="{{ old('full_name') }}"
                    style="width: 100%; padding: 12px 15px; border: 1px solid #dee2e6; border-radius: 5px; font-size: 0.95em; background-color: #fff;"
                    required
                >
            </div>

            <div style="margin-bottom: 20px;">
                <label for="username" style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; font-size: 0.95em;">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    autocomplete="username"
                    style="width: 100%; padding: 12px 15px; border: 1px solid #dee2e6; border-radius: 5px; font-size: 0.95em; background-color: #fff;"
                    required
                >
            </div>

            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; font-size: 0.95em;">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    style="width: 100%; padding: 12px 15px; border: 1px solid #dee2e6; border-radius: 5px; font-size: 0.95em; background-color: #fff;"
                    required
                >
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label for="password" style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; font-size: 0.95em;">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        style="width: 100%; padding: 12px 15px; border: 1px solid #dee2e6; border-radius: 5px; font-size: 0.95em; background-color: #fff;"
                        required
                    >
                    <div style="font-size: 0.8em; color: #6c757d; margin-top: 5px;">At least 6 characters</div>
                </div>

                <div>
                    <label for="password_confirmation" style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; font-size: 0.95em;">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        style="width: 100%; padding: 12px 15px; border: 1px solid #dee2e6; border-radius: 5px; font-size: 0.95em; background-color: #fff;"
                        required
                    >
                </div>
            </div>

            <button type="submit" style="width: 100%; background-color: #495057; color: white; padding: 12px; border: none; border-radius: 5px; font-size: 1em; font-weight: 500; cursor: pointer; margin-bottom: 20px;">
                Create Account
            </button>
        </form>

        <div style="text-align: center; padding-top: 20px; border-top: 1px solid #e9ecef;">
            <a href="{{ route('login') }}" style="color: #495057; text-decoration: none; font-size: 0.9em; margin: 0 10px;">Already have an account? Sign in</a>
            <span>|</span>
            <a href="{{ route('resume.show') }}" style="color: #495057; text-decoration: none; font-size: 0.9em; margin: 0 10px;">View Resume (Public)</a>
        </div>
    </div>
</div>

<style>
    input:focus {
        outline: none;
        border-color: #495057 !important;
        box-shadow: 0 0 0 2px rgba(73, 80, 87, 0.1) !important;
    }

    button:hover {
        background-color: #343a40 !important;
    }

    button:active {
        transform: translateY(1px);
    }

    a:hover {
        text-decoration: underline !important;
    }

    @media (max-width: 480px) {
        div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');

    function validatePassword() {
        if (password.value != confirmPassword.value) {
            confirmPassword.setCustomValidity("Passwords don't match");
        } else {
            confirmPassword.setCustomValidity('');
        }
    }

    password.onchange = validatePassword;
    confirmPassword.onkeyup = validatePassword;
</script>
@endsection
