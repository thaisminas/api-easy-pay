<?php

namespace App\Application\UseCases;

use App\Application\Factory\WalletFactory;
use App\Application\Interfaces\CustomerInterface;
use App\Application\Interfaces\WalletInterface;
use App\Domain\Wallet;

class GetBalanceByCustomer
{
    private $walletInterface;
    private $customerInterface;
    private $walletFactory;

    public function __construct(
        WalletInterface   $walletInterface,
        CustomerInterface $customerInterface,
        WalletFactory     $walletFactory
    )
    {
        $this->walletInterface = $walletInterface;
        $this->customerInterface = $customerInterface;
        $this->walletFactory = $walletFactory;
    }

    public function execute(int $customerId): Wallet
    {
        $customer = $this->customerInterface->findById($customerId);
        $walletMapper = $this->walletInterface->findAccountBalanceByCustomerId($customerId);

        return $this->walletFactory->fromDatabase($walletMapper, $customer);
    }
}