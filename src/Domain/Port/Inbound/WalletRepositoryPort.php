<?php

namespace App\Domain\Port\Inbound;

use App\Domain\Customer;
use App\Domain\Wallet;
use App\Infra\Repository\Mappers\WalletMapper;

interface WalletRepositoryPort
{
    public function save(Customer $customer, array $operation): WalletMapper;
}