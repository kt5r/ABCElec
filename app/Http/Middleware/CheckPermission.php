<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Convert single permission to array
        if (count($permissions) === 1 && !is_array($permissions[0])) {
            $permissions = [$permissions[0]];
        }

        if (!$user->hasAnyPermission($permissions)) {
            abort(403, 'Access denied. You do not have the required permissions.');
        }

        return $next($request);
    }
}
