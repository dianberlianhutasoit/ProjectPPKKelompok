<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Atur tujuan awal setelah login, beda role beda arah
    public function index(Request $request)
    {
        $user = $request->user();

        // Admin ke halaman kelola akun
        if ($user->role === 'ADMIN') {
            return redirect()->route('admin.users.index');
        }

        // STAFF ke halaman pengelolaan reservasi
        if ($user->role === 'STAFF') {
            return redirect()->route('staff.reservations.index');
        }

        // USER ke riwayat reservasinya
        return redirect()->route('reservations.index');
    }
}
