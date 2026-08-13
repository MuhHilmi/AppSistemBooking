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
    /** public function handle($request, Closure $next, $role)
     * {
     *     if(auth()->user()->role != $role)
     *     {
     *         abort(403);
     *     }
     *
     *     return $next($request);
     * }
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response
    {
        if (! auth()->check()) {
            abort(403);
        }

        // $allowedRoles = explode(',', $roles);

        if (! in_array(auth()->user()->role, $roles, true)) {
            abort(403);
        }

        // Akun penjaga yang dinonaktifkan owner tidak boleh mengakses apa pun lagi.
        if (auth()->user()->isPenjaga() && ! auth()->user()->is_active) {
            auth()->logout();
            abort(403, 'Akun Anda telah dinonaktifkan oleh owner.');
        }

        return $next($request);
    }
}
