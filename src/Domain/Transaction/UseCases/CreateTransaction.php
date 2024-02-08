<?php

namespace App\Domain\Transaction\UseCases;

use App\Domain\Transaction\Port\Inbound\TransactionRepository;
use App\Domain\Transaction\Transaction;


class CreateTransaction
{
    private $transactionRepository;

    public function __construct(TransactionRepository $userRepository)
    {
        $this->transactionRepository = $userRepository;
    }

    public function create(Array $data): void
    {
        $payeeId = $data['payeeId'];
        $payeerId = $data['payeerId'];

        $customers = $this->transactionRepository->findCustomerByPayeeAndPayeer($payeeId, $payeerId);

        $transaction = new Transaction();
        $transaction->setPayee($customers[$payeeId]);
        $transaction->setPayeer($customers[$payeerId]);
        $transaction->setAmount($data['amount']);
        $transaction->setOperationType($data['operationType']);

        $this->transactionRepository->create($transaction);
    }
}