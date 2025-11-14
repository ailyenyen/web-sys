@extends('layouts.app')

@section('title', 'Login - Resume System')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; background-color: #f8f9fa;">
    <div style="background-color: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); width: 100%; max-width: 400px; border: 1px solid #e9ecef;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #2c3e50; font-size: 1.8em; margin-bottom: 8px; font-weight: 600;">Welcome Back</h1>
            <p style="color: #6c757d; font-size: 0.95em;">Sign in to view the resume</p>
        </div>

        @if($errors->any())
            <div style="padding: 12px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: 500; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div style="padding: 12px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: 500; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

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
                <label for="password" style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; font-size: 0.95em;">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    style="width: 100%; padding: 12px 15px; border: 1px solid #dee2e6; border-radius: 5px; font-size: 0.95em; background-color: #fff;"
                    required
                >
            </div>

            <button type="submit" style="width: 100%; background-color: #495057; color: white; padding: 12px; border: none; border-radius: 5px; font-size: 1em; font-weight: 500; cursor: pointer; margin-bottom: 20px;">
                Login
            </button>
        </form>

        <div style="text-align: center; padding-top: 20px; border-top: 1px solid #e9ecef;">
            <a href="{{ route('signup') }}" style="color: #495057; text-decoration: none; font-size: 0.9em; margin: 0 10px;">Don't have an account? Sign up</a>
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
        .signup-container {
            padding: 30px 20px !important;
        }
    }
</style>
@endsection
