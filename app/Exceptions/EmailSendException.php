<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class EmailSendException extends Exception
{




    public function report(Throwable $exception)
    {
        parent::report($exception);
    }


    public function render($request)
    {  

     return response()->json([
                    'error' => "Failed!  Email Sending exception occured!"
                    
                ]);
    }
}
