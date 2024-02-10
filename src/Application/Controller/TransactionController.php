<?php

namespace App\Application\Controller;

use App\Application\UseCases\CreateTransaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    private $createTransactionUseCase;

    public function __construct(CreateTransaction $createTransaction)
    {
        $this->createTransactionUseCase = $createTransaction;
    }

    /**
     * @Route("/transaction", name="createTransaction", methods={"POST"})
     */
    public function createTransaction(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $this->createTransactionUseCase->execute($data);

            return new JsonResponse([
                'message' => 'transaction created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Error $error){
            throw new $error;
        }
    }
}