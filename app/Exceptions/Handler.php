<?php

namespace App\Exceptions;

use Exception;
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
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }
        
        $generalError = function($e){
           $customPayload = $e->getMessage();
           $customPayload = json_decode($customPayload,true);
           return [
             'status_code' =>  $customPayload['status_code'], 
             'message'     => $customPayload['message'],
             'payload'     => $customPayload['payload']
           ];
           
        };
        $customError = function($e){
            return [
                    'status_code' =>700,
                    'message'=>$e->getMessage(),
                    'payload'=>[
                    'file'    => $e->getFile(),
                    'line'     =>$e->getLine()]
                    ];
        };
        
        $output =  ($e->getCode() == 0)?  $generalError($e) : $customError($e);
        
        return response()->json($output);
                
    }
}

                    