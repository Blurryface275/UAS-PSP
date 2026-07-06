<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    //

    // Display login form 
    public function login(){
        return view('auth.login');
    }

    public function register(){
        return view('auth.register');
    }

    public function storeRegister(Request $request){
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(), // ini cek ke HaveiBeenPwned, klo bocor ya error
            ],
        ]);

        $profilePicturePath = 'profiles/default.png';
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profiles', 'public');
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
            'profile_picture' => $profilePicturePath,
            'failed_login_attempts' => 0
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil didaftarkan. Silakan login dengan akun Anda.');
    }

    // Handle authenticate process
    // Kalau 5x salah login, akun di lock selama 1 jam
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // throttleKey mengambil email dan ip untuk mencegah brute force attack
        $throttleKey = mb_strtolower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan login. Akun Anda diblokir selama 1 jam. Coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($throttleKey); // Hapus bloker jika berhasil
            $request->session()->regenerate();
            $role = Auth::user()->role;
            if ($role === 'administrator' || $role === 'pegawai') {
                return redirect()->intended('/dashboard');
            } else {
                return redirect()->intended('/'); // Nanti kita arahkan ke tempat customer
            }
        }

        RateLimiter::hit($throttleKey, 3600); // 3600 detik = 1 jam

        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate(); // hapus session pas logout
        $request->session()->regenerateToken(); // bikin token baru waktu logout
        return redirect('/login');
    }
}
