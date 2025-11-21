<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            // Usuario no autenticado
            return redirect('/login');
        }

        $user = Auth::user();
        if ($user->rol && in_array($user->rol->nombre, $roles)) {
            return $next($request);
        }

        // Usuario no tiene el rol adecuado
        return redirect('/unauthorized');
    }
}
