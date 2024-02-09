<?php

namespace App\Domain\Port\Inbound;

use Psr\Http\Message\ResponseInterface;

interface NotificationClientPort
{
    public function postNotification(string $data): ResponseInterface;

}