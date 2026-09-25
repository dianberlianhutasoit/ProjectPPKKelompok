<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Daftar semua akun, yang PENDING tampil paling atas
    public function index(Request $request)
    {
        $status = $request->query('status');

        $users = User::when(
            $status,
            fn ($q) => $q->where('status', $status)
        )
            ->orderByRaw("
                CASE status
                    WHEN 'PENDING' THEN 1
                    WHEN 'ACTIVE' THEN 2
                    WHEN 'REJECTED' THEN 3
                    ELSE 4
                END
            ")
            ->latest()
            ->get();

        return view('admin.users.index', compact('users', 'status'));
    }

    // Admin: form buat akun STAFF / USER baru (langsung aktif)
    public function create()
    {
        return view('admin.users.create');
    }

    // Admin: simpan akun baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:STAFF,USER',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'ACTIVE',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Akun ' . $validated['role'] . ' berhasil dibuat dan langsung aktif.'
            );
    }

    // Admin: setujui atau tolak akun hasil daftar mandiri
    public function verify(Request $request, User $user)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $user->update([
            'status' => $validated['action'] === 'approve'
                ? 'ACTIVE'
                : 'REJECTED',
        ]);

        $message = $validated['action'] === 'approve'
            ? 'Akun ' . $user->email . ' diverifikasi (ACTIVE).'
            : 'Akun ' . $user->email . ' ditolak (REJECTED).';

        return back()->with('success', $message);
    }

    // Admin: nonaktifkan akun pengguna
    public function destroy(User $user)
    {
        // Mencegah Admin menonaktifkan akun sendiri
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
            ->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        // Mengubah status akun menjadi REJECTED/Nonaktif
        $user->update([
            'status' => 'REJECTED',
        ]);

        // Opsi jika ingin menghapus permanen dari database:
        // $user->delete();

        return redirect()->route('admin.users.index')
        ->with('success', 'Akun ' . $user->email . ' berhasil dinonaktifkan.');
    }
}