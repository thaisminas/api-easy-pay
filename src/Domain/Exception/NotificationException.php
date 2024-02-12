<?php
namespace App\Domain\Exception;
use Exception;
use Throwable;

class NotificationException extends Exception {
    public function __construct($message = 'Notification cannot be sent', $code = 400, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}