<?php

namespace ECEPharmacyTree\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Session;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
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
        // if( $e instanceof \Illuminate\Session\TokenMismatchException ) {
        //     return response()->view('errors.token_mismatch', [], 551);

        // }else if( $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException ||
        //             $e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException){
        //     return response()->view('errors.404');

        // }else if( $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ){
        //     return response()->view('errors.403',  [], 403);
        // }

        return parent::render($request, $e);
    }
}
