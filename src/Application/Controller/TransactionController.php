<?php

namespace App\Application\Controller;

use App\Application\UseCases\CreateTransaction;
use App\Application\UseCases\GetExtractByCustomer;
use App\Infra\Repository\Mappers\TransactionMapper;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;

class TransactionController extends AbstractController
{
    private $createTransaction;
    private $getExtractUseCase;

    public function __construct(
        CreateTransaction $createTransaction,
        GetExtractByCustomer $getExtractUseCase
    )
    {
        $this->createTransaction = $createTransaction;
        $this->getExtractUseCase = $getExtractUseCase;
    }

    /**
     * @Route("/api/transaction", name="createTransaction", methods={"POST"})
     * @OA\Post(description="Create new transaction")
     * @OA\RequestBody(@Model(type=TransactionMapper::class, groups={"default"}))
     * @OA\Response(
     *        response=200,
     *        description="Success message"
     *   )
     * @OA\Response(
     *         response=429,
     *         description="Too Many Requests"
     *    )
     * @OA\Response(
     *        response=201,
     *        description="Created"
     *   )
     * @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *     )
     * @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *     )
     * @OA\Tag(name="transaction")
     */
    public function createTransaction(Request $request,  RateLimiterFactory $anonymousApiLimiter): JsonResponse
    {
        $limiter = $anonymousApiLimiter->create($request->getClientIp());
        $limit = $limiter->consume();
        $headers = [
            'X-RateLimit-Remaining' => $limit->getRemainingTokens(),
            'X-RateLimit-Retry-After' => $limit->getRetryAfter()->getTimestamp() - time(),
            'X-RateLimit-Limit' => $limit->getLimit(),
        ];

        if (false === $limit->isAccepted()) {
            return new JsonResponse([
                'error' => 'Too many requests',
                'headers' => $headers
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        try {
            $data = json_decode($request->getContent(), true);

            $this->createTransaction->execute($data);

            return new JsonResponse([
                'message' => 'transaction created successfully',
                'headers' => $headers
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            $code = $e->getCode() ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            return new JsonResponse(['error' => $e->getMessage()], $code);
        }
    }

    /**
     * @Route("/transaction/extract/{customerId}", name="getExtractByCustomer", methods={"GET"})
     * @OA\Get(description="obter extrato por cliente")
     * @OA\Response(
     *       response="200",
     *     description="OK",
     *       @OA\Schema(ref="#/components/schemas/TransactionMapper")
     *   )
     * @OA\Tag(name="transaction")
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