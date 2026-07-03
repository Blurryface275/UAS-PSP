<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    // Display login form 
    public function login(){
        return view('auth.login');
    }

    // Handle authenticate process
    // Kalau 5x salah login, akun di lock selama 1 jam
    public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]); // di sini textbox email dan password gabole kosong, dan email harus format email

        // Check if user is locked
        $user = User::where('email', $credentials['email'])->first();
        if ($user && $user->isLocked()) {
            return back()->with('error', 'Akun Anda terkunci. Silahkan coba lagi dalam 1 jam.');
        }

        // Attempt to authenticate
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role == 'customer') {
                return redirect()->intended(route('customer.dashboard'));
            } else {
                return redirect()->intended(route('dashboard'));
            }
        }

        // Increment failed login attempts
        if ($user) {
            $user->increment('failed_login_attempts');
            if ($user->failed_login_attempts >= 5) {
                $user->lock();
                return back()->with('error', 'Terlalu banyak percobaan login. Akun Anda terkunci selama 1 jam.');
            }
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
