<?php

namespace App\Infra\Client;

use App\Domain\Interfaces\ServiceAuthorizationInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class ServiceAuthorizationClient implements ServiceAuthorizationInterface
{
    /**
     * @var Client
     */
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }
    public function getAuthorization(): ResponseInterface
    {
        $endpoint = $_ENV['URL_SERVICE_AUTHORIZATION'];

        try {
            return $this->httpClient->get($endpoint);

        } catch (\Exception $e) {
            throw new \Exception('Error consult authorization' . $e->getMessage());
        }
    }
}
