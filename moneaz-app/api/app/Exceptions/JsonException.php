<?php

namespace App\Exceptions;

use Throwable;

class JsonException extends \RuntimeException {

    private $http_status_code;

    /**
     * @return mixed
     */
    public function getHttpStatusCode()
    {
        return $this->http_status_code;
    }

    /**
     * @param mixed $http_status_code
     */
    public function setHttpStatusCode($http_status_code): void
    {
        $this->http_status_code = $http_status_code;
    }

    public function __construct($http_status_code, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setHttpStatusCode($http_status_code);

    }

}
