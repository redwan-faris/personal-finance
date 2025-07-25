<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\Exceptions\InvalidIncludeQuery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

        // custom authentication errors messages
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return error($e->getMessage(), 401);
            }
        });

        // custom not found errors messages for page not found and model not found
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return error('Model not found', 404);
            }
        });

        // custom not found errors for route not found
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return error('Route not found', 404);
            }
        });

        // custom validations errors messages
        $this->renderable(function (ValidationException $exception, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => $exception->errors(),
                ], $exception->status);
            }
        });

        // custom throttle errors messages
        $this->renderable(function (ThrottleRequestsException $exception, $request) {
            if ($request->is('api/*')) {
                return error($exception->getMessage(), 429);
            }
        });

        // custom method not allowed errors messages
        $this->renderable(function (MethodNotAllowedHttpException $exception, $request) {
            if ($request->is('api/*')) {
                return error($exception->getMessage(), 405);
            }
        });

        // custom method not allowed errors messages
        $this->renderable(function (InvalidIncludeQuery $exception, $request) {
            if ($request->is('api/*')) {
                return error($exception->getMessage(), 405);
            }
        });
        $this->renderable(function (UnauthorizedException $exception, $request){
            if ($request->is('api/*')){
                return error($exception->getMessage(),403);
            }
        });
        $this->renderable(function (HttpException $exception, $request){
            if ($request->is('api/*')){
                return error($exception->getMessage(),$exception->getStatusCode());
            }
        });
       $this->renderable(function (Throwable $exception, $request){
           if ($request->is('api/*')){
                return error($exception->getMessage(),500);
               if (app()->environment('local')) {
                   return error($exception->getMessage(),500);
               }
               return error('Some thing goes wrong.',500);
           }
       });
    }
}
