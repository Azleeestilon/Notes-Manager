<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import to allow saving to the database
use Illuminate\Support\Facades\Auth; // For login session handling
use Illuminate\Support\Facades\Hash; // For password encryption
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // 1. Display the registration form
    public function showRegister()
    {
        return view('register'); 
    }

    // 2. Save to the database when the registration form is submitted
    public function register(Request $request)
    {
        // SECURE VALIDATION:
        // Added the 'confirmed' rule to check if 'password' matches 'password_confirmation'
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], 
        ]);

        // Save the new user into the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encrypted
        ]);

        // Redirect to the login page with a success message banner
        return redirect()->route('login')->with('success', 'Registration successful! You can now sign in.');
    }

    // 3. Display the login form
    public function showLoginForm()
    {
        return view('login'); 
    }

    // 4. Process the user login request
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // 5. Process the user logout request
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}