<?php

namespace App\Application\UseCases;

use App\Domain\Customer;
use App\Domain\Exception\UnauthorizedOperationException;
use App\Domain\Port\Inbound\CustomerRepositoryPort;
use App\Domain\Port\Inbound\NotificationClientPort;
use App\Domain\Port\Inbound\ServiceAuthorizationPort;
use App\Domain\Port\Inbound\TransactionRepositoryPort;
use App\Domain\Port\Inbound\WalletRepositoryPort;
use App\Domain\Transaction;


class CreateTransaction
{
    private $customerRepository;

    private $trasactionRepository;

    private $serviceAuthorizationPort;

    private $notificationClientPort;
    private $walletRepositoryPort;

    public function __construct(
        TransactionRepositoryPort $transactionRepository,
        CustomerRepositoryPort    $customerRepository,
        ServiceAuthorizationPort  $serviceAuthorizationPort,
        NotificationClientPort    $notificationClientPort,
        WalletRepositoryPort  $walletRepositoryPort
    )
    {
        $this->trasactionRepository = $transactionRepository;
        $this->customerRepository = $customerRepository;
        $this->serviceAuthorizationPort = $serviceAuthorizationPort;
        $this->notificationClientPort = $notificationClientPort;
        $this->walletRepositoryPort = $walletRepositoryPort;
    }

    public function create(Array $data): void
    {
        $customers = $this->customerRepository->findCustomerByPayeeAndPayeer($data['payeeId'], $data['payeerId']);
        $transaction = $this->transaction($customers, $data);

        $this->validateOperation($transaction, $customers, $data);

        $situation = $this->serviceAuthorizationPort->getAuthorization();

        $status = $this->notificationClientPort->postNotification('any data');

        $this->trasactionRepository->create($transaction);
    }


    private function transaction(array $customers, array $data): Transaction
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

    private function validateOperation(Transaction $transaction, array $customers, array $operation): void
    {
        if($transaction->getPayeer()->getRole() === 'PJ'){
            throw new UnauthorizedOperationException('User cannot perform this operation');
        }


        foreach ($customers as $customer){
            $customer;
        }

        $payeeAccountBalance = $this->walletRepositoryPort->findCustomerByPayeeAndPayeer($transaction);

        $payeeAccountBalance;

    }
}