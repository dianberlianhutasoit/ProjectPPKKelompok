<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$user) {  // cek apakah user udah login
            return redirect('/login');  // kalo belum, lempar ke login
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
