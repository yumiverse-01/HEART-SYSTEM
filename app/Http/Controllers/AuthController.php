<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Update last login timestamp
            Auth::login($user);
            $user->load('role');
            $user->update(['last_login' => now()]);

            Session::put('user_id', $user->user_id);

            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password) && ($user->role?->name === 'Super Admin' || $user->role?->name === 'Admin')) {
            Auth::login($user);
            $user->load('role');
            $user->update(['last_login' => now()]);

            Session::put('user_id', $user->user_id);

            return redirect('/admin/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials or insufficient permissions']);
    }

    public function logout(Request $request)
    {
        $isAdmin = in_array(Auth::user()->role?->name, ['Super Admin', 'Admin']);
        
        Auth::logout();
        Session::flush();

        return $isAdmin ? redirect()->route('admin.login') : redirect()->route('login');
    }
}