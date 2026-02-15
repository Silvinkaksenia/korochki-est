<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('login', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'login' => 'Неверный логин или пароль.',
        ])->onlyInput('login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $request->validate([
        'login' => 'required|string|min:6|max:50|regex:/^[a-zA-Z0-9]+$/|unique:users',
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'full_name' => 'required|string|regex:/^[а-яА-ЯёЁ\s]+$/u',
        'phone' => [
            'required',
            'string',
            'regex:/^\+7\s\(\d{3}\)\s\d{3}-\d{2}-\d{2}$/', // Формат: +7 (999) 123-45-67
            'unique:users'
        ],
        'email' => 'required|email|unique:users',
    ]);

    $user = User::create([
        'login' => $request->login,
        'password' => Hash::make($request->password),
        'full_name' => $request->full_name,
        'phone' => $request->phone, // Сохраняем как +7 (999) 123-45-67
        'email' => $request->email,
        'is_admin' => false,
    ]);

    Auth::login($user);

    return redirect()->route('dashboard');
}
    

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
