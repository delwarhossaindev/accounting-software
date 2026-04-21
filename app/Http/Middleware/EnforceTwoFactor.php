<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->two_factor_enabled && !session('two_factor_passed')) {
            if (!$request->routeIs('2fa.*') && !$request->routeIs('logout')) {
                return redirect()->route('2fa.challenge');
            }
        }

        return $next($request);
    }
}
