<?php

namespace App\Application\Controller;

use App\Application\UseCases\CreateCustomer;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
        }  catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['error' => 'Violation: Name and email must be unique in the database - ' . $e->getMessage()], Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            $code = $e->getCode() ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            return new JsonResponse(['error' => $e->getMessage()], $code);
        }
    }
}