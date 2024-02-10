<?php

namespace App\Application\Controller;

use App\Application\UseCases\DepositUseCase;
use App\Application\UseCases\GetBalanceByCustomer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    private $depositUseCase;
    private $getBalanceUseCase;

    public function __construct(DepositUseCase $depositUseCase, GetBalanceByCustomer $getBalanceUseCase)
    {
        $this->depositUseCase = $depositUseCase;
        $this->getBalanceUseCase = $getBalanceUseCase;
    }


    /**
     * @Route("/wallet/deposit", name="deposit")
     */
    public function deposit(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $customer = $this->depositUseCase->execute($data);

        $responseData = [
            'message' => 'Deposit successfully',
            'customer' => $customer
        ];

        return new JsonResponse($responseData, Response::HTTP_CREATED);
    }

    /**
     * @Route("/wallet/balance/{customerId}", name="balance")
     */
    public function getBalanceByCustomer(int $customerId): JsonResponse
    {
        $wallet = $this->getBalanceUseCase->execute($customerId);

        if (!$wallet) {
            return new JsonResponse(['error' => 'Wallet for customer not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $responseData = [
            'id' => $wallet->getId(),
            'name' => $wallet->getCustomer()->getName(),
            'balance' => $wallet->getAccountBalance()
        ];

        return new JsonResponse($responseData, JsonResponse::HTTP_OK);
    }
}