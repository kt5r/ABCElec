<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Services\CartService;

class CartMiddleware
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $cartCount = 0;
        
        if (auth()->check()) {
            $cartCount = $this->cartService->getCartItemCount();
        }

        View::share('cartCount', $cartCount);

        return $next($request);
    }
}
