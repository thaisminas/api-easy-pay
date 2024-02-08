<?php

namespace App\Domain\Transaction\UseCases;

use App\Domain\Transaction\Port\Inbound\TransactionRepository;
use App\Infra\Repository\Doctrine\CustomerEntityDatabase;
use App\Infra\Repository\Mappers\Customer;
use App\Infra\Repository\Mappers\Transaction;

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

        $filteredCustomers = array_filter($customers, function (Customer $customer) use ($payeeId, $payeerId) {
            return $customer->getId() == $payeeId || $customer->getId() == $payeerId;
        });

        var_dump($filteredCustomers);

        $customer = 'Buscar o payee do banco de dados';
        $payeer = 'buscar o payeer do banco';


        $transaction = new Transaction();
        $transaction->setPayee($customer);
        $transaction->setPayeer($payeer);
        $transaction->setAmount($data['amount']);
        $transaction->setOperationType($data['operationType']);


        $this->transactionRepository->create($transaction);
    }
}