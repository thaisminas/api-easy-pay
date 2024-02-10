<?php

namespace App\Domain\Exception;
use Exception;
use Throwable;

class ServiceUnauthorizedException extends Exception {
    public function __construct($message = 'Service Unauthorized', $code = 403, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}