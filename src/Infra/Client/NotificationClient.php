<?php

namespace App\Infra\Client;

use App\Domain\Interfaces\NotificationInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class NotificationClient implements NotificationInterface
{
    /**
     * @var Client
     */
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    public function postNotification(string $data): ResponseInterface
    {
        $endpoint = $_ENV['URL_NOTIFICATION_CLIENT'];

        try {
            return $this->httpClient->post($endpoint,  [
                'json' => $data
            ]);

        } catch (RequestException $e) {
            throw new \Exception('Error send notification' . $e->getMessage());
        }
    }
}
