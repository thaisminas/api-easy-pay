<?php

namespace App\Infra\Client;

use App\Application\Interfaces\NotificationInterface;
use Exception;
use GuzzleHttp\Client;

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

    public function postNotification(string $data): bool
    {
        $endpoint = 'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6';

        try {
            $response = $this->httpClient->post($endpoint,  [
                'json' => $data
            ]);

            $message = json_decode($response->getBody()->getContents(), true)['message'];

            if(!$message){
                return false;
            }

            return $message;
        }catch (Exception $e){
            throw new Exception('Error try send message');
        }

    }
}
