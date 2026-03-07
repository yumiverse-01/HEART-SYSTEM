<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Update last login timestamp
            $user->update(['last_login' => now()]);
            
            // Store user info in session
            Session::put('user_id', $user->user_id);
            Session::put('user_name', $user->first_name . ' ' . $user->last_name);
            Session::put('user_role', $user->role);

            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout() {
        Session::flush();
        return redirect('/login');
    }
}