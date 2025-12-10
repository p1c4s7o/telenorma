<?php

namespace Exception;

use Throwable;

class MethodNotAllowed extends \Exception
{
    public function __construct(string $message = "Method Not Allowed", int $code = 405, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}