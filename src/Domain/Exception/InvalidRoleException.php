<?php

namespace App\Domain\Exception;

use Exception;
use Throwable;

class InvalidRoleException extends Exception {
    public function __construct($message = 'Customer type does not exist', $code = 422, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}