<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use BadMethodCallException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //

        });
    }






public function render($request, Throwable $exception)
{
    // return parent::render($request, $exception);
    // dd($exception);
    if($request->is('api/*')){
        if($exception instanceof BadMethodCallException ){
            return response()->json(['status'=>false, 'message'=> "Internal server error"], 500);
        }
        if ($exception instanceof HttpException) {
            $message = "";
            $statusCode = $exception->getStatusCode();
            switch ($statusCode) {
                case 404:
                    $message = "Request not found";
                    break;
                case 401:
                    $message = "Unauthorized not found";
                    break;
                case 500:
                    $message = "Request not found";
                    break;
                default:
                    $message = "Some error occured";
                    break;
            }
            return response()->json(['status'=>false, 'message' => $message], $statusCode);
        }
    }
    return parent::render($request, $exception);
}

}
