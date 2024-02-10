<?php

namespace App\Domain\Exception;
use Exception;
use Throwable;

class CustomerAlreadyExistException extends Exception {
    public function __construct($message = 'Already Registered Customer ', $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}