<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SalesManagerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', __('messages.authentication_required'));
        }

        $user = Auth::user();

        // Check if user has sales_manager, admin, or operation_manager role
        if (!in_array($user->role, ['sales_manager', 'admin', 'operation_manager'])) {
            abort(403, __('messages.access_denied'));
        }

        return $next($request);
    }
    // public function handle(Request $request, Closure $next)
    // {
    //     if (!Auth::check()) {
    //         return redirect()->route('login');
    //     }

    //     $user = Auth::user();
        
    //     if (!$user->role->isSalesManager() && !$user->role->isAdmin()) {
    //         abort(403, 'Access denied. Sales Manager role required.');
    //     }

    //     return $next($request);
    // }
}