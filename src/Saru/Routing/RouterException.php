<?php


namespace Saru\Routing;
use Exception;
use Throwable;


class RouterException extends Exception
{
    private $httpCode;

    /**
     * @return int Return the HTTP Code for Response Handling
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    public function __construct($message = "", $code = 0, Throwable $previous = null, $httpCode = 500)
    {
        parent::__construct($message, $code, $previous);
        $this->httpCode = $httpCode;
    }
}