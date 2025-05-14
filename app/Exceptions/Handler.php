<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        // Handle ModelNotFoundException (findOrFail)
        $this->renderable(function (ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', 'The requested record was not found.');
        });

        // Handle NotFoundHttpException (404)
        $this->renderable(function (NotFoundHttpException $e) {
            return redirect()->route('home')
                ->with('error', 'The requested page was not found.');
        });

        // Handle AuthenticationException
        $this->renderable(function (AuthenticationException $e) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this page.');
        });

        // Handle ValidationException
        $this->renderable(function (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Please check the form for errors.');
        });

        // Handle other exceptions
        $this->renderable(function (Throwable $e) {
            if (config('app.debug')) {
                return null; // Let Laravel handle the exception normally in debug mode
            }

            return redirect()->back()
                ->with('error', 'An unexpected error occurred. Please try again later.');
        });
    }
}
