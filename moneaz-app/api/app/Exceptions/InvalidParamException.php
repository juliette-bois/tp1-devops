<?php

namespace App\Exceptions;

use JsonException;
use Throwable;

class InvalidParamException extends \App\Exceptions\JsonException {

    public function __construct($paramName = "", $code = 0, Throwable $previous = null)
    {
        $message = "Invalid or missing parameter '$paramName'";

        parent::__construct(400, $message, $code, $previous);
    }

}
