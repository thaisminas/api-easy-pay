<?php

namespace App\Application\Factory;

use App\Domain\Transaction;

class TransactionFactory
{
    public static function createFromArray(array $customers, array $data): Transaction
    {
        $payeeId = $data['payeeId'];
        $payeerId = $data['payeerId'];

        $transaction = new Transaction();
        $transaction->setPayee($customers[$payeeId]);
        $transaction->setPayeer($customers[$payeerId]);
        $transaction->setAmount($data['amount']);
        $transaction->setOperationType($data['operationType']);

        return $transaction;
    }
}