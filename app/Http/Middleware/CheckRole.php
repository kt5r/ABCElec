<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Convert single role to array
        if (count($roles) === 1 && !is_array($roles[0])) {
            $roles = [$roles[0]];
        }

        if (!$user->hasRole($roles)) {
            abort(403, 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }
}