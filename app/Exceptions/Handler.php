<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\Response as Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $statusCode = 700;
        $payload = [];
        
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        } elseif ($e instanceof HttpException) {
            if ($e->getTrace()[0]['function'] == 'interrupt') {
                $statusCode = $e->getTrace()[0]['args'][0];
            }
        }
        
        $payload = ($statusCode == 700)?
                [ 'file' => $e->getFile(), 'line' => $e->getLine()] : [];
                                        
        $response = Response::respond($statusCode, $e->getMessage(), $payload);
        
        return response()->json($response);
                
    }
}
