<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedRedirect
{
    /**
     * Handle an incoming request.
     * Redirect users based on their roles after login
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Skip redirect for logout route
            if ($request->routeIs('logout')) {
                return $next($request);
            }

            // Redirect based on role after login
            if ($request->routeIs('dashboard') || $request->routeIs('home')) {
                switch ($user->getRoleName()) {
                    case 'admin':
                    case 'operation_manager':
                        if ($request->routeIs('home')) {
                            return redirect()->route('admin.dashboard');
                        }
                        break;
                    case 'sales_manager':
                        if ($request->routeIs('home') || $request->routeIs('dashboard')) {
                            return redirect()->route('admin.reports.sales');
                        }
                        break;
                    case 'customer':
                        if ($request->routeIs('dashboard')) {
                            return redirect()->route('home');
                        }
                        break;
                }
            }
        }

        return $next($request);
    }
}