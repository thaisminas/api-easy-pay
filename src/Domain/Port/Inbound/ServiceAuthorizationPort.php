<?php


namespace App\Domain\Port\Inbound;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

interface ServiceAuthorizationPort
{
    public function getAuthorization(): ResponseInterface;

}