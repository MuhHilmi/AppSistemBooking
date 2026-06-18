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
        string $role
    ): Response
    {
        if (! auth()->check()) {
            abort(403);
        }

        if (auth()->user()->role !== $role) {
            abort(403);
        }

        return $next($request);
    }
}
