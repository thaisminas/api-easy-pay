<?php

namespace App\Domain\Port\Inbound;

use Symfony\Component\HttpFoundation\Response;

interface NotificationClientPort
{
    public function postNotification(string $data): Response;

}