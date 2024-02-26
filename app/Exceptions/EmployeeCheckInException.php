
<?php

namespace App\Exceptions;

use Exception;

class EmployeeCheckInException extends Exception
{
    protected $message;
    protected $statusCode;

    public function __construct($message, $statusCode = 422)
    {
        parent::__construct($message, $statusCode);
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    public function render($request)
    {
        return response()->json(['message' => $this->message], $this->statusCode);
    }
}
