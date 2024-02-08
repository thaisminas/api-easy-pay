<?php

namespace App\Application\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HealthyController
{
    /**
     * @Route("/api/healthy", name="healthy")
     */
    public function healthy(): JsonResponse
    {

        return new JsonResponse([
            'message' => 'Server running!',
            'status' => 'ok'
        ]);
    }
}