<?php

namespace App\Application\Controller;

use App\Application\UseCases\CreateTransaction;
use App\Application\UseCases\GetExtractByCustomer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    private $createTransaction;
    private $getExtractUseCase;

    public function __construct(CreateTransaction $createTransaction, GetExtractByCustomer $getExtractUseCase)
    {
        $this->createTransaction = $createTransaction;
        $this->getExtractUseCase = $getExtractUseCase;
    }

    /**
     * @Route("/transaction/extract/{customerId}", name="getExtractByCustomer", methods={"GET"})
     */
    public function createTransaction(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $this->createTransaction->execute($data);

            return new JsonResponse([
                'message' => 'transaction created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            $code = $e->getCode() ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            return new JsonResponse(['error' => $e->getMessage()], $code);
        }
    }

    /**
     * @Route("/transaction/extract/{customerId}", name="getExtractByCustomer", methods={"GET"})
     */
    public function getExtractByCustomer(int $customerId): JsonResponse
    {
        $extract = $this->getExtractUseCase->execute($customerId);

        if (!$extract) {
            return new JsonResponse(['error' => 'Extract not found for customer'], JsonResponse::HTTP_NOT_FOUND);
        }

        $responseData = [
            'id' => $customerId,
            'extract' => $extract,
        ];

        return new JsonResponse($responseData, JsonResponse::HTTP_OK);
    }
}