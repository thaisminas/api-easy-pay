<?php

namespace App\Application\UseCases;

use App\Application\Interfaces\CustomerInterface;
use App\Application\Interfaces\WalletInterface;
use App\Infra\Repository\Mappers\WalletMapper;

class DepositUseCase
{
    private $walletInterface;
    private $customerInterface;

    public function __construct(WalletInterface $walletInterface, CustomerInterface $customerInterface)
    {
        $this->walletInterface = $walletInterface;
        $this->customerInterface = $customerInterface;
    }
    public function execute(array $operation): WalletMapper
    {
        $customerById = $this->customerInterface->findById($operation['customer']);

        return $this->walletInterface->save($customerById, $operation);
    }
}