<?php

namespace App\Application\Controller;

use App\Application\UseCases\CreateCustomer;
use App\Infra\Repository\Mappers\CustomerMapper;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;


class CustomerController extends AbstractController
{
    private $createCustomer;

    public function __construct(CreateCustomer $createCustomer)
    {
        $this->createCustomer = $createCustomer;
    }

    /**
     * @Route("/api/customer", methods={"POST"})
     * @OA\Post(description="Create new customer")
     * @OA\RequestBody(@Model(type=CustomerMapper::class, groups={"create"}))
     * @OA\Response(
     *       response=201,
     *       description="Created"
     *  )
     * @OA\Response(
     *        response=409,
     *        description="Conflict"
     *   )
     * @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *    )
     * @OA\Tag(name="customer")
     */
    public function createCustomer(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);

            $customer = $this->createCustomer->execute($data);

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