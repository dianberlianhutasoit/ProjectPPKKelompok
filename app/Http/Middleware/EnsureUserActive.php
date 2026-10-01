<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserActive
{
    // Pastikan yang login statusnya ACTIVE, kalau tidak langsung logout
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status !== 'ACTIVE') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = match ($user->status) {
                'PENDING' => 'Akun menunggu verifikasi admin.',
                'REJECTED' => 'Akun ditolak admin, tidak dapat digunakan.',
                'INACTIVE' => 'Akun dinonaktifkan admin, hubungi admin untuk aktivasi kembali.',
                default => 'Akun tidak aktif, tidak dapat digunakan.',
            };

            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        return $next($request);
    }
}
