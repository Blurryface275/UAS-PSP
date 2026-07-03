<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password; // OWASP Password Strength

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.member.index', compact('users'));
    }

    // Hanya tampilkan form (tidak ada logic di sini!)
    public function create()
    {
        return view('admin.member.create');
    }

    // Eksekusi simpan ke DB (inilah yang dipanggil saat form di-submit via POST)
    public function store(Request $request)
    {
        // Validasi ketat (OWASP: Input Validation)
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            // OWASP Password Standard: min 8 char, besar, kecil, angka, simbol
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()   // Wajib ada huruf BESAR & kecil
                    ->numbers()     // Wajib ada angka
                    ->symbols()     // Wajib ada simbol (!@#$...)
                    ->uncompromised(), // Cek ke database HaveiBeenPwned (bocor atau tidak)
            ],
            'role' => ['required', Rule::in(['administrator', 'pegawai', 'customer'])],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), 
            // role default adalah customer, tapi kalau dari form admin bisa diubah
            'role'     => $request->role??'customer',
        ]);

        return redirect()->route('users.index')->with('success', 'Akun karyawan baru berhasil diterbitkan!');
    }

    public function show(string $id)
    {
        // Tidak dipakai, bisa dikosongkan
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.member.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Validasi update: email boleh sama dengan miliknya sendiri
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'  => ['required', Rule::in(['administrator', 'pegawai', 'customer'])],
            // Password opsional saat edit, tapi jika diisi wajib memenuhi standar OWASP
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        // Hanya ganti password jika admin mengisinya
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Proteksi IDOR: Admin tidak boleh menghapus akun dirinya sendiri!
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak boleh menghapus akun Anda sendiri!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun karyawan berhasil dihapus!');
    }
}
