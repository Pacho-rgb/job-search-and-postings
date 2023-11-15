<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Show register form
    public function register()
    {
        return view('users.register');
    }

    // Store register form data from the request
    public function store(Request $request)
    {
        // Input validation
        $formFiels = $request->validate([
            'name' => ['required', 'min:5'], 
            'password' => ['required', 'confirmed', 'min:6'],
            'email' => ['required', 'email', Rule::unique('users', 'email')]
            ]);
        
            // Hashing password before we store to the database
            $formFiels['password'] = bcrypt($formFiels['password']);

            // We are going to register the user then automatically login
            $user = User::create($formFiels);

            // Login
            auth()->login($user);

            // Redirect to the homepage
            return redirect('/')->with('message', 'Registered and logged in successfully');
    }

    // Logout a user
    public function logout(Request $request)
    {
        // Remove the authentication data from user session
        auth()->logout();

        // Flush the session data and regenerate the ID.
        $request->session()->invalidate();

        // Regenerate the CSRF token value.
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'Logged out!');
    }

    // Show the login form
    public function login()
    {
        return view('users.login');
    }

    // Authenticate the user
    public function authenticate(Request $request)
    {
        // Input validation
        $formFiels = $request->validate([ 
            'password' => ['required'],
            'email' => ['required', 'email'] 
            ]);

            // Attempt to authenticate a user using the given credentials.
            if(auth()->attempt($formFiels)){
                // Generate a new session identifier.
                $request->session()->regenerate();

                return redirect('/')->with('message', 'Logged in successfully');
            }else{
                return back()->withErrors([
                    'email' => 'invalid credentials',
                    'password' => 'invalid credentials'
                ]);
            }
    }
}
