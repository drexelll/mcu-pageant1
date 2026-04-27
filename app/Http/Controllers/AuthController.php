<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Redirect to Microsoft
    public function redirect()
    {
        return Socialite::driver('microsoft')->redirect();
    }

    // Handle callback from Microsoft
    public function callback()
    {
        try {
            $msUser = Socialite::driver('microsoft')->user();
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Microsoft login failed.');
        }

        // Find or create user
        $user = User::where('email', $msUser->getEmail())->first();

        if (!$user) {
            return redirect('/')->with('error', 'Access denied. Your account is not registered in the system.');
        }

        if ($user->status !== 'active') {
            return redirect('/')->with('error', 'Your account is inactive. Contact the administrator.');
        }

        Auth::login($user);

        // Redirect based on role
        return match($user->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'judge'     => redirect()->route('judge.dashboard'),
            'sas'       => redirect()->route('sas.dashboard'),
            default     => redirect('/')->with('error', 'Unknown role assigned.'),
        };
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    // Redirect to Google
public function redirectGoogle()
{
    return Socialite::driver('google')->redirect();
}

// Handle callback from Google
public function callbackGoogle()
{
    try {
        $googleUser = Socialite::driver('google')->user();
    } catch (\Exception $e) {
        return redirect('/')->with('error', 'Google login failed.');
    }

    $user = User::where('email', $googleUser->getEmail())->first();

    if (!$user) {
        return redirect('/')->with('error', 'Access denied. Your account is not registered in the system.');
    }

    if ($user->status !== 'active') {
        return redirect('/')->with('error', 'Your account is inactive. Contact the administrator.');
    }

    Auth::login($user);

    return match($user->role) {
        'admin'  => redirect()->route('admin.dashboard'),
        'judge'  => redirect()->route('judge.dashboard'),
        'sas'    => redirect()->route('sas.dashboard'),
        default  => redirect('/')->with('error', 'Unknown role assigned.'),
    };
}

public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ], [
        'email.required'    => 'Email is required.',
        'email.email'       => 'Please enter a valid email address.',
        'password.required' => 'Password is required.',
    ]);

    // Check if user exists
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->with('error', 'Access denied. Your account is not registered in the system.');
    }

    if ($user->status !== 'active') {
        return back()->with('error', 'Your account is inactive. Contact the administrator.');
    }

    // Attempt login
    if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput($request->only('email'));
    }

    // Redirect based on role
    return match($user->role) {
        'admin'  => redirect()->route('admin.dashboard'),
        'judge'  => redirect()->route('judge.dashboard'),
        'sas'    => redirect()->route('sas.dashboard'),
        default  => redirect('/')->with('error', 'Unknown role assigned.'),
    };
}
}
