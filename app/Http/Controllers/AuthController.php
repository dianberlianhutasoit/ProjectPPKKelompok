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
    // Email yang pernah REJECTED boleh daftar ulang: pakai record lama, tanpa INSERT baru
    public function register(Request $request)
    {
        // Cek input di server biar aman (tanpa unique:users,email karena
        // email REJECTED boleh daftar ulang — pengecekan manual di bawah)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $existing = User::where('email', $validated['email'])->first();

        if ($existing) {
            // Registrasi ulang: pakai record lama, update data terbaru,
            // kembalikan ke PENDING dan hapus alasan penolakan lama
            if ($existing->status === 'REJECTED') {
                $existing->update([
                    'name' => $validated['name'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'USER',
                    'status' => 'PENDING',
                    'rejection_reason' => null,
                ]);

                return redirect()->route('login')->with('success', 'Registrasi ulang berhasil. Akun menunggu verifikasi admin sebelum bisa login.');
            }

            $message = match ($existing->status) {
                'PENDING' => 'Email ini sudah terdaftar dan masih menunggu verifikasi admin.',
                'INACTIVE' => 'Akun dengan email ini dinonaktifkan. Silakan hubungi admin.',
                default => 'Email ini sudah terdaftar. Silakan login.',
            };

            return back()->withErrors(['email' => $message])->onlyInput('email');
        }

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

        // Akun selain ACTIVE belum boleh login, langsung logout lagi
        $user = Auth::user();
        if ($user->status !== 'ACTIVE') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = match ($user->status) {
                'PENDING' => 'Akun menunggu verifikasi admin.',
                'REJECTED' => 'Akun ditolak admin, tidak dapat digunakan.',
                'INACTIVE' => 'Akun dinonaktifkan admin, hubungi admin untuk aktivasi kembali.',
                default => 'Akun tidak aktif, tidak dapat digunakan.',
            };

            return back()->withErrors(['email' => $message])->onlyInput('email');
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
