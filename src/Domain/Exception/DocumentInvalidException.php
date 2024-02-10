<?php
namespace App\Domain\Exception;

use Exception;
use Throwable;

class DocumentInvalidException extends Exception {
    public function __construct($message = 'Document Invalid', $code = 422, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}