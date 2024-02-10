<?php

namespace App\Domain\Interfaces;

use Psr\Http\Message\ResponseInterface;

interface NotificationInterface
{
    public function postNotification(string $data): ResponseInterface;

}