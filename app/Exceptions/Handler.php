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
            
           
           $customPayload = substr($e->getMessage(), 0,strrpos($e->getMessage(), '}')+1) ;
           $customPayload = json_decode($customPayload,true);
           
           $intendedErrorPayload = function($e) {
                $customPayload = substr($e->getMessage(), 0,strrpos($e->getMessage(), '}')+1) ;
                $customPayload = json_decode($customPayload,true);
                    return [
                'status_code' =>  $customPayload['status_code'], 
                'message'     => $customPayload['message'],
                'payload'     => $customPayload['payload']
              ];
           };
           
           $internalErrorPayload  = function($e) {
               
                 return [
             'status_code' =>  700,
             'message'     => $e->getMessage(),
             'payload'     => [
                 'file' => $e->getFile(),
                 'line' => $e->getLine()
             ]
           ];
           };
           
           return ($customPayload['status_code'] == null && $customPayload['message'] == null && 
                   $customPayload['payload'] == null )?  $internalErrorPayload($e):
                                            $intendedErrorPayload($e);
                                                        
          
           
        };
        
        $systemError = function($e){
            return [
                    'status_code' =>700,
                    'message'=>$e->getMessage(),
                    'payload'=>[
                        'file'    => $e->getFile(),
                        'line'     =>$e->getLine()]
                    ];
        };
        
        $output =  ($e->getCode() == 0 )?  $generalError($e) : $systemError($e);
        
        return response()->json($output);
                
    }
}

                    