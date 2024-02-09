<?php

namespace App\Application\Controller;

use App\Application\UseCases\CreateCustomer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private $createCustomerUseCase;

    public function __construct(CreateCustomer $createCustomer)
    {
        $this->createCustomerUseCase = $createCustomer;
    }

    /**
     * @Route("/api/customer", methods={"POST"})
     */
    public function createCustomer(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $customer = $this->createCustomerUseCase->createCustomer($data);

        $responseData = [
            'message' => 'User created successfully',
            'customer' => $customer
        ];

        return new JsonResponse($responseData, Response::HTTP_CREATED);
    }
}