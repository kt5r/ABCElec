<?php

namespace App\Http\Traits;

trait HasLocaleMiddleware
{
    /**
     * Apply locale middleware to the controller
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
}