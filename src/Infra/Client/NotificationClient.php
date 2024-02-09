<?php

namespace App\Infra\Client;

use App\Domain\Port\Inbound\NotificationClientPort;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class NotificationClient implements NotificationClientPort
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
        $endpoint = 'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6';

        try {
            return $this->httpClient->post($endpoint,  [
                'json' => $data
            ]);

        } catch (RequestException $e) {
            throw new \Exception('Error send notification' . $e->getMessage());
        }
    }
}
