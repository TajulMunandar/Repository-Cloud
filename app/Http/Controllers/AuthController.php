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
        return view('pages.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun tidak aktif!']);
            }
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function showRegister()
    {
        return view('pages.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'nullable|string|max:100',
            'username'   => 'required|string|max:50|unique:users',
            'email'      => 'required|email|max:100|unique:users',
            'password'   => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'username'   => $request->username,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
