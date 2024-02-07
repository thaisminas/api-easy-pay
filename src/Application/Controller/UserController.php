<?php

namespace App\Application\Controller;

use App\Application\UseCases\CreateUser;
use App\Infra\Repository\Doctrine\UserEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $createUserUseCase;

    public function __construct(CreateUser $createUserUseCase)
    {
        $this->createUserUseCase = $createUserUseCase;
    }

    /**
     * @Route("/api/users", methods={"POST"})
     */
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new UserEntity();
        $user->setName($data['name']);
        $user->setSsn($data['ssn']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRole($data['role']);

        $this->createUserUseCase->createUser($user);

        return new JsonResponse(['message' => 'User created successfully'], Response::HTTP_CREATED);
    }
}