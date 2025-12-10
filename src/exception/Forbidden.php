<?php

namespace Exception;

use Throwable;

class Forbidden extends \Exception
{
    public function __construct(string $message = "Forbidden", int $code = 403, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}