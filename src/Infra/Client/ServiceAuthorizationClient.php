<?php

namespace App\Infra\Client;

use App\Domain\Port\Inbound\ServiceAuthorizationPort;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class ServiceAuthorizationClient implements ServiceAuthorizationPort
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
        $endpoint = 'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc';

        try {
            return $this->httpClient->get($endpoint);

        } catch (\Exception $e) {
            throw new \Exception('Error consult authorization' . $e->getMessage());
        }
    }
}
