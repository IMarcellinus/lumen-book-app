<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Response; // Add this line
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson() && !($exception instanceof ValidationException)) {
            $response = [
                'message' => (string) $exception->getMessage(),
                'status' => 400
            ];

            if ($exception instanceof HttpException) {
                $response['message'] = Response::$statusTexts[$exception->getStatusCode()];
                $response['status'] = $exception->getStatusCode();
            } elseif ($exception instanceof ModelNotFoundException) {
                $response['message'] = \Symfony\Component\HttpFoundation\Response::$statusTexts[\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND]; // Adjusted namespace
                $response['status'] = \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND; // Adjusted namespace
            }

            if ($this->isDebugMode()) {
                $response['debug'] = [
                    'exception' => get_class($exception),
                    'trace' => $exception->getTrace(),
                ];
            }

            return response()->json(['error' => $response], $response['status']);
        }

        return parent::render($request, $exception);
    }

    public function isDebugMode()
    {
        return (Boolean) env('APP_DEBUG');
    }

}