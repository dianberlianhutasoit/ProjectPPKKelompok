<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show pending accounts first.
    public function index(Request $request)
    {
        $status = $request->query('status');

        $users = User::when($status, fn ($q) => $q->where('status', $status))
            ->orderByRaw("CASE status WHEN 'PENDING' THEN 1 WHEN 'ACTIVE' THEN 2 WHEN 'REJECTED' THEN 3 ELSE 4 END")
            ->latest()
            ->get();

        return view('admin.users.index', compact('users', 'status'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if (! in_array($this->emailDomain($value), ['students.undip.ac.id', 'undip.ac.id'], true)) {
                        $fail('Gunakan email resmi UNDIP: students.undip.ac.id atau undip.ac.id.');
                    }
                },
            ],
            'password' => 'required|string|min:8',
            'role' => [
                'required',
                'in:STAFF,USER',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === 'STAFF' && $this->emailDomain($request->input('email')) === 'students.undip.ac.id') {
                        $fail('Email students.undip.ac.id hanya dapat digunakan untuk akun USER.');
                    }
                },
            ],
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
            ->with('success', 'Akun '.$validated['role'].' berhasil dibuat dan langsung aktif.');
    }

    private function emailDomain(mixed $email): ?string
    {
        if (! is_string($email) || ! str_contains($email, '@')) {
            return null;
        }

        return strtolower(substr(strrchr($email, '@'), 1));
    }

    // Rejection needs a reason; approval clears any old one.
    public function verify(Request $request, User $user)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:1000',
        ]);

        if ($validated['action'] === 'approve') {
            $user->update([
                'status' => 'ACTIVE',
                'rejection_reason' => null,
            ]);

            $message = 'Akun '.$user->email.' diverifikasi (ACTIVE).';
        } else {
            $user->update([
                'status' => 'REJECTED',
                'rejection_reason' => trim($validated['rejection_reason']),
            ]);

            $message = 'Akun '.$user->email.' ditolak (REJECTED).';
        }

        return back()->with('success', $message);
    }

    // Keep account history when deactivating it.
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['status' => 'INACTIVE']);

        return back()->with('success', 'Akun '.$user->email.' dinonaktifkan (INACTIVE).');
    }
}
