<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect('/login');
        }

        // Role names are case-insensitive.
        $userRole = strtoupper($user->role);
        $allowedRoles = array_map('strtoupper', $roles);

        if (! in_array($userRole, $allowedRoles)) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
