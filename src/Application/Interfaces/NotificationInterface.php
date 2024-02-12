<?php

namespace App\Application\Interfaces;

interface NotificationInterface
{
    public function postNotification(string $data): bool;

}