<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
        $this->renderable(function (Throwable $e) {
            if ($e instanceof ModelNotFoundException) {
                return redirect()->back()
                    ->with('error', 'Record not found.')
                    ->with('sweetAlert', [
                        'type' => 'error',
                        'title' => 'Error!',
                        'text' => 'The requested record was not found.'
                    ]);
            }

            if ($e instanceof NotFoundHttpException) {
                return redirect()->route('home')
                    ->with('error', 'Page not found.')
                    ->with('sweetAlert', [
                        'type' => 'error',
                        'title' => '404 Error!',
                        'text' => 'The requested page was not found.'
                    ]);
            }

            if ($e instanceof ValidationException) {
                return redirect()->back()
                    ->withErrors($e->validator)
                    ->withInput()
                    ->with('sweetAlert', [
                        'type' => 'error',
                        'title' => 'Validation Error!',
                        'text' => 'Please check the form for errors.'
                    ]);
            }

            if ($e instanceof AuthenticationException) {
                return redirect()->route('login')
                    ->with('sweetAlert', [
                        'type' => 'warning',
                        'title' => 'Authentication Required!',
                        'text' => 'Please login to continue.'
                    ]);
            }

            if ($e instanceof AuthorizationException) {
                return redirect()->back()
                    ->with('sweetAlert', [
                        'type' => 'error',
                        'title' => 'Unauthorized!',
                        'text' => 'You are not authorized to perform this action.'
                    ]);
            }

            // Handle any other exceptions
            if (config('app.debug')) {
                return parent::render(request(), $e);
            }

            return redirect()->back()
                ->with('sweetAlert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'An unexpected error occurred. Please try again.'
                ]);
        });
    }
}
