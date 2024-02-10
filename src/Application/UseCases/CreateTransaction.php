<?php

namespace App\Application\UseCases;

use App\Application\Factory\TransactionFactory;
use App\Application\Interfaces\CustomerInterface;
use App\Application\Interfaces\ServiceAuthorizationInterface;
use App\Application\Interfaces\TransactionInterface;
use App\Application\Interfaces\WalletInterface;
use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\Exception\OperationTypeException;
use App\Domain\Exception\UnauthorizedOperationException;
use App\Domain\Transaction;
use Exception;


class CreateTransaction
{
    const STORE_USER = 'STORE';
    const CREDIT = 'CREDIT';
    const DEBIT = 'DEBIT';

    private $transactionInterface;
    private $customerInterface;
    private $serviceAuthorization;
    private $walletInterface;
    private $transactionFactory;
    private $notificationUseCase;

    public function __construct(
        TransactionInterface          $transactionInterface,
        CustomerInterface             $customerRepository,
        ServiceAuthorizationInterface $serviceAuthorization,
        WalletInterface               $walletInterface,
        TransactionFactory   $transactionFactory,
        SendNotification     $notificationUseCase
    )
    {
        $this->transactionInterface = $transactionInterface;
        $this->customerInterface = $customerRepository;
        $this->serviceAuthorization = $serviceAuthorization;
        $this->walletInterface = $walletInterface;
        $this->transactionFactory = $transactionFactory;
        $this->notificationUseCase = $notificationUseCase;
    }

    public function execute(Array $data): void
    {
        $customers = $this->customerInterface->findCustomerByPayeeAndPayeer($data['payeeId'], $data['payeerId']);

        $transaction = $this->transactionFactory->createFromArray($customers, $data);
        $wallets = $this->walletInterface->findCustomerByPayeeAndPayeer($transaction);
        $this->validateTransaction($transaction, $wallets);
        $this->serviceAuthorization->getAuthorization();

        try {
            $this->updateBalances($wallets, $transaction, -1);
            $this->transactionInterface->create($transaction);
            $this->notificationUseCase->execute();
        } catch (Exception $err){
            $this->updateBalances($wallets, $transaction, 1);
            throw new Exception('An error occurred while trying to process the transaction');
        }
    }

    private function validateTransaction(Transaction $transaction, array $wallets): void
    {
        $operationType = $transaction->getOperationType();

        if($transaction->getPayeer()->getRole() === self::STORE_USER){
            throw new UnauthorizedOperationException('User cannot perform this operation');
        }

        if($transaction->getAmount() > $wallets[$transaction->getPayeer()->getId()]->getAccountBalance()){
            throw new InsufficientBalanceException('Insufficient balance to carry out the operation');
        }

        if($operationType !== self::CREDIT && $operationType !== self::DEBIT){
            throw new OperationTypeException('The operation type is invalid');
        }

        if($transaction->getAmount() <= 0){
            throw new OperationTypeException('The amount transaction is invalid');
        }
    }

    private function updateBalances(array $wallets, Transaction $transaction, int $amountModifier): void
    {
        $payeerId = $transaction->getPayeer()->getId();
        $payeeId = $transaction->getPayee()->getId();
        $amount = $transaction->getAmount();

        $balancePayeer = $wallets[$payeerId]->getAccountBalance();
        $balancePayee = $wallets[$payeeId]->getAccountBalance();

        $wallets[$payeerId]->setAccountBalance(round(($balancePayeer + $amountModifier * $amount), 2));
        $wallets[$payeeId]->setAccountBalance(round(($balancePayee - $amountModifier * $amount), 2));

        $balanceData = balanceDataFormatter($wallets, $transaction);

        $this->walletInterface->updateAccountBalanceByCustomerId($balanceData);
    }
}