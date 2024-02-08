<?php

namespace App\Domain\Transaction\Port\Inbound;

use App\Domain\Transaction\Transaction;

interface TransactionRepository
{
    public function create(Transaction $transaction): void;
    public function findCustomerByPayeeAndPayeer(int $payeeId, int $payeerId);
}