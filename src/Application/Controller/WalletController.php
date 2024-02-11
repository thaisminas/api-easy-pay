<?php

namespace App\Application\Controller;

use App\Application\UseCases\DepositUseCase;
use App\Application\UseCases\GetBalanceByCustomer;
use App\Infra\Repository\Mappers\WalletMapper;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;

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
     * @OA\Post(description="Add balance in wallet"),
     * @OA\RequestBody(@Model(type=WalletMapper::class, groups={"default"}))
     * @OA\Response(
     *       response=201,
     *       description="Created"
     *  )
     * @OA\Tag(name="wallet")
     */
    public function deposit(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $customer = $this->depositUseCase->execute($data);

            $responseData = [
                'message' => 'Deposit successfully',
                'customer' => $customer
            ];

            return new JsonResponse($responseData, Response::HTTP_CREATED);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['error' => 'Violation: Balance for customer must be unique in the database - ' . $e->getMessage()], Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            $code = $e->getCode() ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            return new JsonResponse(['error' => $e->getMessage()], $code);
        }
    }

    /**
     * @Route("/wallet/balance/{customerId}", name="balance")
     * @OA\Get(description="Get Balance by customer")
     * @OA\Response(
     *      response="201",
     *     description="OK",
     *      @OA\Schema(ref="#/components/schemas/WalletMapper")
     *  )
     * @OA\Response(
     *        response=404,
     *        description="Not Found"
     *   )
     * @OA\Tag(name="wallet")
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