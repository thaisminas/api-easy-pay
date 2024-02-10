<?php

namespace App\Application\UseCases;

use App\Application\Factory\TransactionFactory;
use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\NotificationException;
use App\Domain\Exception\OperationTypeException;
use App\Domain\Exception\ServiceUnauthorizedException;
use App\Domain\Exception\UnauthorizedOperationException;
use App\Domain\Interfaces\CustomerRepository;
use App\Domain\Interfaces\NotificationInterface;
use App\Domain\Interfaces\ServiceAuthorizationInterface;
use App\Domain\Interfaces\TransactionRepository;
use App\Domain\Interfaces\WalletRepository;
use App\Domain\Transaction;
use Exception;


class CreateTransaction
{
    const STORE_USER = 'STORE';
    const CREDIT = 'CREDIT';
    const DEBIT = 'DEBIT';
    const STATUS_OK = 200;
    private $customerRepository;

    private $trasactionRepository;

    private $serviceAuthorization;

    private $notificationInterface;
    private $walletRepository;
    private $transactionFactory;

    public function __construct(
        TransactionRepository         $transactionRepository,
        CustomerRepository            $customerRepository,
        ServiceAuthorizationInterface $serviceAuthorization,
        NotificationInterface         $notificationInterface,
        WalletRepository              $walletRepository,
        TransactionFactory            $transactionFactory
    )
    {
        $this->trasactionRepository = $transactionRepository;
        $this->customerRepository = $customerRepository;
        $this->serviceAuthorization = $serviceAuthorization;
        $this->notificationInterface = $notificationInterface;
        $this->walletRepository = $walletRepository;
        $this->transactionFactory = $transactionFactory;
    }

    public function execute(Array $data): void
    {
        $customers = $this->customerRepository->findCustomerByPayeeAndPayeer($data['payeeId'], $data['payeerId']);

        if(count($customers) <= 1){
            throw new NotFoundException('Customer not found for create transaction');
        }

        $transaction = $this->transactionFactory->createFromArray($customers, $data);
        $wallets = $this->walletRepository->findCustomerByPayeeAndPayeer($transaction);

        try {
            $this->validateTransaction($transaction, $wallets);

            $situation = $this->serviceAuthorization->getAuthorization();

            if($situation->getStatusCode() !== self::STATUS_OK){
                throw new ServiceUnauthorizedException('unauthorized transaction');
            }

            $this->amountToDeductBalance($wallets, $transaction);
            $this->trasactionRepository->create($transaction);
            $this->sendNotification();
        } catch (\Error $err){
            $this->reverseAmountDeductedFromBalance($wallets, $transaction);

            throw new \Exception($err);
        }
    }

    private function sendNotification(): void
    {
        $maxRetries = 3;
        $retryCount = 0;
        $notificationSent = false;

        while (!$notificationSent && $retryCount < $maxRetries) {
            try {
                $this->notificationInterface->postNotification(json_encode([
                    'message' => 'transfer sent!',
                ]));

                $notificationSent = true;

            } catch (Exception $e) {
                $retryCount++;
                sleep(1);
            }
        }

        if (!$notificationSent) {
            throw new NotificationException('Failed to send notification after 3 retries.');
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

    private function updateBalances(array $wallets, Transaction $transaction, $amountModifier): void
    {
        $payeerId = $transaction->getPayeer()->getId();
        $payeeId = $transaction->getPayee()->getId();
        $amount = $transaction->getAmount();

        $balancePayeer = $wallets[$payeerId]->getAccountBalance();
        $balancePayee = $wallets[$payeeId]->getAccountBalance();

        $wallets[$payeerId]->setAccountBalance(round(($balancePayeer + $amountModifier * $amount), 2));
        $wallets[$payeeId]->setAccountBalance(round(($balancePayee - $amountModifier * $amount), 2));

        $balanceData = balanceDataFormatter($wallets, $transaction);

        $this->walletRepository->updateAccountBalanceByCustomerId($balanceData);
    }

    private function amountToDeductBalance(array $wallets, Transaction $transaction)
    {
        $this->updateBalances($wallets, $transaction, -1);
    }

    private function reverseAmountDeductedFromBalance(array $wallets, Transaction $transaction)
    {
        $this->updateBalances($wallets, $transaction, 1);
    }
}