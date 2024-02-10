<?php

namespace App\Application\UseCases;

use App\Domain\Interfaces\CustomerRepository;
use App\Domain\Interfaces\WalletRepository;
use App\Infra\Repository\Mappers\WalletMapper;

class DepositUseCase
{
    private $depositRepository;
    private $customerRepository;

    public function __construct(WalletRepository $depositRepository, CustomerRepository $userRepository)
    {
        $this->depositRepository = $depositRepository;
        $this->customerRepository = $userRepository;
    }
    public function execute(array $operation): WalletMapper
    {
        $customerById = $this->customerRepository->findById($operation['customer']);

        return $this->depositRepository->save($customerById, $operation);
    }
}