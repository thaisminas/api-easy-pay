<?php


namespace App\Domain\Interfaces;

use Psr\Http\Message\ResponseInterface;

interface ServiceAuthorizationInterface
{
    public function getAuthorization(): ResponseInterface;

}