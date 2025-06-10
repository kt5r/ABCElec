<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle 404 errors
        if ($e instanceof NotFoundHttpException) {
            return response()->view('errors.404', [
                'exception' => $e,
                'title' => __('messages.page_not_found')
            ], 404);
        }

        // Handle 403 errors
        if ($e instanceof AccessDeniedHttpException) {
            return response()->view('errors.403', [
                'exception' => $e,
                'title' => __('messages.access_denied')
            ], 403);
        }

        // Handle validation errors for AJAX requests
        if ($request->expectsJson() && $e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => __('messages.validation_failed'),
                'errors' => $e->errors()
            ], 422);
        }

        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into a response.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthenticated')
            ], 401);
        }

        // Redirect based on the guard that was attempted
        $guard = $exception->guards()[0] ?? null;
        
        switch ($guard) {
            case 'admin':
                $login = 'admin.login';
                break;
            default:
                $login = 'login';
                break;
        }

        return redirect()->guest(route($login))
            ->with('warning', __('messages.please_login_to_continue'));
    }

    /**
     * Get the default context variables for logging.
     */
    protected function context(): array
    {
        return array_merge(parent::context(), [
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
        ]);
    }
}