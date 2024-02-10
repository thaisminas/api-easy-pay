<?php

namespace App\Domain\Interfaces;

use App\Domain\Transaction;

interface TransactionRepository
{
    public function create(Transaction $transaction): void;
    public function findExtractByCustomer(int $customerId): array;
}