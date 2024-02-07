<?php

namespace App\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transactions", name="create_transaction", methods={"POST"})
     */
    public function createTransaction(): JsonResponse
    {

        $response = [
            'message' => 'Transaction created successfully.',
        ];

        return $this->json($response, JsonResponse::HTTP_CREATED);
    }
}