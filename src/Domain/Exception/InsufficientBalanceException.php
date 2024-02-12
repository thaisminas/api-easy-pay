<?php

namespace App\Domain\Exception;
use Exception;
use Throwable;

class InsufficientBalanceException extends Exception {
    public function __construct($message = 'Insufficient Balance Exception', $code = 400, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}