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
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ])->withInput($request->only('email'));
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $role = auth()->user()->role;
            if($role == 'admin') {
                return redirect('/admin/dashboard');
            } else if($role == 'owner') {
                return redirect('/owner/dashboard');
            }
            return redirect('/customer/dashboard');
        }

        return back()->withErrors([
            'password' => 'Password salah.',
        ])->withInput($request->only('email'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $username = strtolower(str_replace(' ', '', $request->name));

        $userData = [
            'username' => $username,
            'firstname' => $request->name,
            'lastname' => '',
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'customer',
            'address' => '',
            'city' => '',
            'country' => '',
            'postal' => '',
            'about' => ''
        ];

        $user = User::create($userData);

        Auth::login($user);

        return redirect('/customer/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
} 