<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // This will replace our 404 response with a JSON response.
    if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
        return response()->json([
            'error' => 'Resource item not found.'
        ], 404);
    }

    if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
        return response()->json([
            'code'=>404,
            'error' => 'Resource not found.'
        ], 404);
    }

    if ($exception instanceof MethodNotAllowedHttpException && $request->wantsJson()) {
        return response()->json([
            'error' => 'Method not allowed.'
        ], 405);
    }
    
        return parent::render($request, $exception);
    }


        protected function unauthenticated($request, AuthenticationException $exception) 
            {
                if ($request->expectsJson()) {
                return response()->json(['response' => 'Unauthenticated.','status_code'=>501,'message'=>'Unauthenticated']);
            }

            return redirect()->guest('login');
        }
}
