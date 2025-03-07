<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAbility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$abilities): Response
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            // abort(403, 'Unauthorized');
            return redirect()->route('login');
        }

        foreach ($abilities as $ability) {
            
            if (!$request->user()->hasPermissionTo($ability)) {
            // if (!$request->user()->hasDepartment($ability)) {
                // abort(403, "Unauthorized to access {$ability}");
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
