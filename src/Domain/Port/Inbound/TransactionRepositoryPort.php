<?php

namespace App\Domain\Port\Inbound;

use App\Domain\Transaction;

interface TransactionRepositoryPort
{
    public function create(Transaction $transaction): void;

}