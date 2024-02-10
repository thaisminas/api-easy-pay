<?php

namespace App\Application\UseCases;

use App\Application\Factory\CustomerFactory;
use App\Application\Factory\WalletFactory;
use App\Domain\Interfaces\CustomerRepository;
use App\Domain\Interfaces\WalletRepository;
use App\Domain\Wallet;

class GetBalanceByCustomer
{
    private $walletRepository;
    private $customerRepository;
    private $customerFactory;
    private $walletFactory;

    public function __construct(
        WalletRepository $walletRepository,
        CustomerRepository $customerRepository,
        CustomerFactory $customerFactory,
        WalletFactory $walletFactory
    )
    {
        $this->walletRepository = $walletRepository;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->walletFactory = $walletFactory;
    }

    public function execute(int $customerId): Wallet
    {
        $customer = $this->customerRepository->findById($customerId);
        $walletMapper = $this->walletRepository->findAccountBalanceByCustomerId($customerId);

        return $this->walletFactory->fromDatabase($walletMapper, $customer);
    }
}