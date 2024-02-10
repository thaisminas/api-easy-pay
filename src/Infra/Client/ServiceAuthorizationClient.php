<?php

namespace App\Infra\Client;

use App\Application\Interfaces\ServiceAuthorizationInterface;
use App\Domain\Exception\ServiceUnauthorizedException;
use Exception;
use GuzzleHttp\Client;

class ServiceAuthorizationClient implements ServiceAuthorizationInterface
{
    const AUTORIZADO = 'Autorizado';

    /**
     * @var Client
     */
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }
    public function getAuthorization(): void
    {
        $endpoint = 'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc';

        $response = $this->httpClient->get($endpoint);
        $body = json_decode($response->getBody()->getContents(), true);

        if ($body['message'] === self::AUTORIZADO) {
            return;
        }

        throw new ServiceUnauthorizedException('unauthorized transaction');
    }
}
