<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // Cek role sebelum masuk halaman (mis. role:ADMIN)
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user(); // siapa yang lagi login

        if (!$user) {  // belum login, lempar ke login
            return redirect('/login');
        }

        if (!in_array($user->role, $roles)) { // role tidak cocok, tolak
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
