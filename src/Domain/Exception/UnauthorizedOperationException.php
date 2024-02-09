<?php

namespace App\Domain\Exception;
use Exception;
use Throwable;

class UnauthorizedOperationException extends Exception {
    public function __construct($message = 'Unauthorized Operation', $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}