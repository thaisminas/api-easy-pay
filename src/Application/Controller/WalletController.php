<?php

namespace App\Application\Controller;

use App\Application\UseCases\DepositUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    private $depositUseCase;

    public function __construct(DepositUseCase $depositUseCase)
    {
        $this->depositUseCase = $depositUseCase;
    }


    /**
     * @Route("/wallet", name="deposit")
     */
    public function deposit(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $customer = $this->depositUseCase->deposit($data);

        $responseData = [
            'message' => 'Deposit successfully',
            'customer' => json_encode($customer)
        ];

        return new JsonResponse($responseData, Response::HTTP_CREATED);
    }
}