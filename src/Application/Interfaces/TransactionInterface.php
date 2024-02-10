<?php

namespace App\Application\Interfaces;

use App\Domain\Transaction;

interface TransactionInterface
{
    public function create(Transaction $transaction): void;
    public function findExtractByCustomer(int $customerId): array;
}