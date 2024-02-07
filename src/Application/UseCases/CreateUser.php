<?php

namespace App\Application\UseCases;

use App\Domain\Repository\CustomerRepository;
use App\Infra\Repository\Doctrine\CustomerEntity;

class CreateUser
{
    private $userRepository;

    public function __construct(CustomerRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(CustomerEntity $user): void
    {
        $this->userRepository->save($user);
    }
}