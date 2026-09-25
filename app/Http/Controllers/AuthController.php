<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    // Daftar akun baru — khusus USER, status awal PENDING (nunggu verifikasi admin)
    public function register(Request $request)
    {
        // Cek input di server biar aman
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'USER',
            'status' => 'PENDING',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Akun menunggu verifikasi admin sebelum bisa login.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Akun PENDING / REJECTED belum boleh login, langsung logout lagi
        $user = Auth::user();
        if ($user->status === 'PENDING') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email' => 'Akun menunggu verifikasi admin.'])->onlyInput('email');
        }

        if ($user->status === 'REJECTED') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email' => 'Akun ditolak admin, tidak dapat digunakan.'])->onlyInput('email');
        }

        return $this->redirectByRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    private function redirectByRole($user)
    {
        // Lempar ke dashboard, biar pembagian per-role diatur di satu tempat
        return redirect()->intended(route('dashboard'));
    }
}
