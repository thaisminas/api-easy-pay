<?php

namespace App\Application\UseCases;

use App\Application\Interfaces\NotificationInterface;
use App\Domain\Exception\NotificationException;

class SendNotification
{
    private $notification;
    public function __construct(NotificationInterface $notification)
    {
        $this->notification = $notification;
    }

    public function execute(): void
    {
        $maxRetries = 3;
        $retryCount = 0;
        $notificationSent = false;

        while (!$notificationSent && $retryCount < $maxRetries) {
            $status = $this->notification->postNotification(json_encode([
                'message' => 'transfer sent!',
            ]));

            $notificationSent = $status;

            if(!$status){
                $retryCount++;
                sleep(1);
            }
        }

        if (!$notificationSent) {
            throw new NotificationException('Failed to send notification after 3 retries.');
        }
    }
}