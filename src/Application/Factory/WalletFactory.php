<?php

namespace App\Application\Factory;

use App\Domain\Customer;
use App\Domain\Wallet;
use App\Infra\Repository\Mappers\WalletMapper;

class WalletFactory
{
    public function toDatabase(int $customer, array $operation): WalletMapper
    {
        $walletMapper = new WalletMapper();
        $walletMapper->customer = $customer;
        $walletMapper->accountBalance = $operation['accountBalance'];
        $walletMapper->updatedAt = new \DateTime();
        return $walletMapper;
    }
    public function fromDatabase(WalletMapper $walletMapper, Customer $customer): Wallet
    {
        $walletEntity = new Wallet();
        isset($walletMapper->id) ? $walletEntity->setId($walletMapper->id) : null;

        $walletEntity->setCustomer($customer);
        $walletEntity->setAccountBalance($walletMapper->accountBalance);

        return $walletEntity;
    }
}