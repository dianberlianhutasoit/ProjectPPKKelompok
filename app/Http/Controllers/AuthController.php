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

    // Registrasi mandiri selalu USER/PENDING. Email REJECTED boleh daftar ulang pada record lama.
    public function register(Request $request)
    {
        // Domain email wajib UNDIP (exact match setelah @).
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $domain = strtolower(substr(strrchr($value, '@') ?: '', 1));

                    if (! in_array($domain, ['students.undip.ac.id', 'undip.ac.id'], true)) {
                        $fail('Gunakan email resmi Universitas Diponegoro.');
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $existing = User::where('email', $validated['email'])->first();

        if ($existing) {
            // REJECTED daftar ulang: pakai record lama, kembali PENDING, hapus alasan penolakan.
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

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Hanya ACTIVE boleh login.
        $user = Auth::user();
        if ($user->status !== 'ACTIVE') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = match ($user->status) {
                'PENDING' => 'Akun menunggu verifikasi admin.',
                'REJECTED' => $user->rejection_reason
                    ? 'Pendaftaran akun ditolak. Alasan: '.$user->rejection_reason.' Silakan daftar ulang setelah memperbaiki data.'
                    : 'Pendaftaran akun ditolak oleh admin.',
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
        return redirect()->intended(route('dashboard'));
    }
}
