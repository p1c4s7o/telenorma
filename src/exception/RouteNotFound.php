<?php

namespace Exception;

use Throwable;

class RouteNotFound extends \Exception
{
    public function __construct(string $message = "Route Not Found", int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}