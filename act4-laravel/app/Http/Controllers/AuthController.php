<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Update last login using direct database update
            User::where('id', Auth::id())->update([
                'last_login' => now()
            ]);

            return redirect()->intended('/resume');
        }

        return back()->withErrors([
            'username' => 'Invalid Username or Password',
        ])->withInput($request->only('username'));
    }

    public function showSignup()
    {
        return view('auth.signup');
    }

    public function signup(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users|min:3|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'full_name' => 'required|min:2|max:100',
        ]);

        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'full_name' => $validated['full_name'],
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully! You can now log in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
