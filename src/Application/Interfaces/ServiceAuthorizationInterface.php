<?php


namespace App\Application\Interfaces;

use Psr\Http\Message\ResponseInterface;

interface ServiceAuthorizationInterface
{
    public function getAuthorization(): void;

}