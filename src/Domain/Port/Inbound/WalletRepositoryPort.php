<?php

namespace App\Domain\Port\Inbound;

use App\Domain\Customer;
use App\Domain\Transaction;
use App\Domain\Wallet;
use App\Infra\Repository\Mappers\WalletMapper;

interface WalletRepositoryPort
{
    public function save(Customer $customer, array $operation): WalletMapper;
    public function findAccountBalanceById(array $operation): Wallet;
    public function findCustomerByPayeeAndPayeer(Transaction $transaction): array;
}