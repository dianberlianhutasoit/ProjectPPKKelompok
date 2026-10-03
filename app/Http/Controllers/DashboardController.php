<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'ADMIN') {
            return redirect()->route('admin.users.index');
        }

        if ($user->role === 'STAFF') {
            return redirect()->route('staff.reservations.index');
        }

        return redirect()->route('reservations.index');
    }
}
