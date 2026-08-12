<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->must_change_password && !$request->routeIs('password.force-change*') && !$request->routeIs('logout')) {
            return redirect()->route('password.force-change.edit');
        }

        return $next($request);
    }
}
