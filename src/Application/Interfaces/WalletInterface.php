<?php

namespace App\Application\Interfaces;

use App\Domain\Customer;
use App\Domain\Transaction;
use App\Infra\Repository\Mappers\WalletMapper;

interface WalletInterface
{
    public function save(Customer $customer, array $operation): WalletMapper;
    public function findAccountBalanceByCustomerId(int $customerId): WalletMapper;
    public function findCustomerByPayeeAndPayeer(Transaction $transaction): array;
    public function updateAccountBalanceByCustomerId(array $customer): void;
}