<?php

namespace App\Application\UseCases;

use App\Domain\Repository\UserRepository;
use App\Infra\Repository\Doctrine\UserEntity;

class CreateUser
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(UserEntity $user): void
    {
        $this->userRepository->save($user);
    }
}