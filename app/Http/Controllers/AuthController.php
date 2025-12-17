<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $input = $request->input('email'); // field name tetap 'email' tapi bisa username
        $password = $request->input('password');

        $isEmail = filter_var($input, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            // Coba login dengan email
            if (Auth::attempt(['email' => $input, 'password' => $password], $request->filled('remember'))) {
                if (!Auth::user()->is_active) {
                    Auth::logout();
                    return back()->withErrors(['email' => 'Akun tidak aktif!']);
                }
                return redirect()->intended('/dashboard');
            }

            // Cek apakah email ada
            $user = User::where('email', $input)->first();
            if (!$user) {
                return back()->withErrors(['email' => 'Email salah']);
            } else {
                return back()->withErrors(['email' => 'Password salah']);
            }
        } else {
            // Coba login dengan username
            if (Auth::attempt(['username' => $input, 'password' => $password], $request->filled('remember'))) {
                if (!Auth::user()->is_active) {
                    Auth::logout();
                    return back()->withErrors(['email' => 'Akun tidak aktif!']);
                }
                return redirect()->intended('/dashboard');
            }

            // Cek apakah username ada
            $user = User::where('username', $input)->first();
            if (!$user) {
                return back()->withErrors(['email' => 'Username salah']);
            } else {
                return back()->withErrors(['email' => 'Password salah']);
            }
        }
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

    public function showForgotPassword()
    {
        return view('pages.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword($token)
    {
        return view('pages.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
