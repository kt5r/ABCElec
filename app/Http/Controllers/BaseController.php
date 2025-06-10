<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseControllerClass;
use App\Http\Traits\HasLocaleMiddleware;

class BaseController extends BaseControllerClass
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, HasLocaleMiddleware;

    public function __construct()
    {
        // Apply locale middleware by default
        $this->applyLocaleMiddleware();
    }

    /**
     * Apply locale middleware - can be called explicitly by child controllers
     */
    protected function applyLocaleMiddleware()
    {
        $this->middleware(function ($request, $next) {
            if (session()->has('locale')) {
                $locale = session('locale');
                if (in_array($locale, ['en', 'si'])) {
                    app()->setLocale($locale);
                }
            }
            return $next($request);
        });
    }

    /**
     * Apply auth middleware - helper method for child controllers
     */
    protected function applyAuthMiddleware()
    {
        $this->middleware('auth');
    }
}