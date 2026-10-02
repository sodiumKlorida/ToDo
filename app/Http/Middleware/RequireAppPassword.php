<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireAppPassword
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('app.password') || $request->routeIs('app.password.submit')) {
            return $next($request);
        }

        if (session('app_password_verified') === true) {
            return $next($request);
        }

        $expectedPassword = env('APP_PASSWORD', 'issueboard123');

        if ($request->isMethod('post') && $request->input('password') === $expectedPassword) {
            session(['app_password_verified' => true]);

            return $next($request);
        }

        return redirect()->route('app.password')->with('error', 'Password salah atau belum diisi.');
    }
}
