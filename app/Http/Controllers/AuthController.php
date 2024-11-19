<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle the login request
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('responses.index'); // Redirect to responses
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Handle the logout request
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login'); // Redirect to login page
    }
}
