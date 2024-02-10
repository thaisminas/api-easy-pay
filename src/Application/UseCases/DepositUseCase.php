<?php

namespace App\Application\UseCases;

use App\Domain\Port\Inbound\CustomerRepositoryPort;
use App\Domain\Port\Inbound\WalletRepositoryPort;
use App\Infra\Repository\Mappers\WalletMapper;

class DepositUseCase
{
    private $depositRepository;
    private $customerRepository;

    public function __construct(WalletRepositoryPort $depositRepository, CustomerRepositoryPort $userRepository)
    {
        $this->depositRepository = $depositRepository;
        $this->customerRepository = $userRepository;
    }
    public function execute(Array $operation): WalletMapper
    {
        $customerById = $this->customerRepository->findById($operation);

        return $this->depositRepository->save($customerById, $operation);
    }
}