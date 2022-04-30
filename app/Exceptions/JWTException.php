<?php

namespace App\Exceptions;

use Exception;

class JWTException extends Exception
{
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            dd($request,$e);
            return response()->json([
                'message' => 'SOME ERROR OCCURED.'
            ], 401);
        }
        return parent::render($request, $e);
    }
}
