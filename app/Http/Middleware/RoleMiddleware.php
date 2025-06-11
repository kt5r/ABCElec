<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated.');
        }

        // Split roles if they are comma-separated
        $allowedRoles = collect($roles)->flatMap(function ($role) {
            return explode(',', $role);
        })->map(function ($role) {
            return trim($role);
        })->toArray();

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}