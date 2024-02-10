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
    public function createCustomer(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);

            $customer = $this->createCustomerUseCase->execute($data);

            $responseData = [
                'customer' => $customer
            ];

            return new JsonResponse($responseData, Response::HTTP_CREATED);
        } catch (\Error $err){
            return new Response('Ocorreu um erro interno do servidor.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}