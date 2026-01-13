<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        try {
            return view('auth.admin.login');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading login page: ' . $e->getMessage());
        }
    }

    public function login(AdminLoginRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Login failed: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('admin')->logout();
            
            $request->session()->regenerateToken();
            
            return redirect()->route('admin.login');
        } catch (\Exception $e) {
            return back()->with('error', 'Logout failed: ' . $e->getMessage());
        }
    }
}
