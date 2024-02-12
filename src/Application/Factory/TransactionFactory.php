<?php

namespace App\Application\Factory;

use App\Domain\Transaction;
use App\Infra\Repository\Mappers\TransactionMapper;

class TransactionFactory
{
    public function createFromArray(array $customers, array $data): Transaction
    {
        $payeeId = $data['payeeId'];
        $payeerId = $data['payeerId'];

        $transaction = new Transaction();
        $transaction->setPayee($customers[$payeeId]);
        $transaction->setPayeer($customers[$payeerId]);
        $transaction->setAmount($data['amount']);
        $transaction->setOperationType(strtoupper($data['operationType']));

        return $transaction;
    }
    public function toDatabaseEntity(Transaction $transaction): TransactionMapper
    {
        $transactionMapper = new TransactionMapper();
        $transactionMapper->payee = $transaction->getPayee()->getId();
        $transactionMapper->payeer = $transaction->getPayeer()->getId();
        $transactionMapper->amount = $transaction->getAmount();
        $transactionMapper->operationType = $transaction->getOperationType();

        return $transactionMapper;
    }
}