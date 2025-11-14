<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResumeController extends Controller
{
    public function show()
    {
        try {
            // Always show user ID 1 (shared resume)
            $user = User::with(['profile', 'education', 'projects', 'skills', 'awards'])->findOrFail(1);

            $isLoggedIn = Auth::check();

            return view('resume.show', compact('user', 'isLoggedIn'));
        } catch (\Exception $e) {
            // For debugging - remove this after fixing
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function edit()
    {
        try {
            // Always edit user ID 1 (shared resume)
            $user = User::with(['profile', 'education', 'projects', 'skills', 'awards'])->findOrFail(1);

            return view('resume.edit', compact('user'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
}
