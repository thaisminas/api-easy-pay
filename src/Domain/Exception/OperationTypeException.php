<?php

namespace App\Domain\Exception;
use Exception;
use Throwable;

class OperationTypeException extends Exception {
    public function __construct($message = 'Type of operation not allowed', $code = 422, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}