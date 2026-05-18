<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Tangani proses register
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|max:50|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'username.unique' => 'Username ini sudah digunakan oleh orang lain!',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan underscore.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Simpan user baru
        $user = User::create([
            'name' => $request->name,
            'username' => strtolower($request->username), // Simpan dalam huruf kecil agar pencarian mudah
            'password' => Hash::make($request->password),
        ]);

        // Log in user secara otomatis setelah registrasi
        Auth::login($user);

        // Alihkan ke halaman dashboard
        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Tangani proses login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Normalisasi username menjadi huruf kecil
        $credentials['username'] = strtolower($credentials['username']);

        // Coba autentikasi
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerasi session ID demi keamanan
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Berhasil login! Selamat kembali.');
        }

        // Jika gagal, kembalikan dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    // Tangani proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        // Bersihkan sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
