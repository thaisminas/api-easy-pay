<?php

namespace App\Application\UseCases;

use App\Application\Factory\TransactionFactory;
use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\Exception\NotificationException;
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
    const COMMON_USER = 'COMMON';
    const STORE_USER = 'STORE';
    private $customerRepository;

    private $trasactionRepository;

    private $serviceAuthorizationInterface;

    private $notificationInterface;
    private $walletRepository;

    public function __construct(
        TransactionRepository         $transactionRepository,
        CustomerRepository            $customerRepository,
        ServiceAuthorizationInterface $serviceAuthorizationInterface,
        NotificationInterface         $notificationInterface,
        WalletRepository              $walletRepository
    )
    {
        $this->trasactionRepository = $transactionRepository;
        $this->customerRepository = $customerRepository;
        $this->serviceAuthorizationInterface = $serviceAuthorizationInterface;
        $this->notificationInterface = $notificationInterface;
        $this->walletRepository = $walletRepository;
    }

    public function execute(Array $data): void
    {
        $customers = $this->customerRepository->findCustomerByPayeeAndPayeer($data['payeeId'], $data['payeerId']);
        $transaction = TransactionFactory::createFromArray($customers, $data);
        $wallets = $this->walletRepository->findCustomerByPayeeAndPayeer($transaction);

        try {
            $this->validateTransaction($transaction, $wallets);

            $situation = $this->serviceAuthorizationInterface->getAuthorization();

            if($situation->getStatusCode() !== 200){
                throw new ServiceUnauthorizedException('unauthorized transaction');
            }

            $this->sendNotification();

            $this->amountToDeductBalance($wallets, $transaction);
            $this->trasactionRepository->create($transaction);

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
            throw new NotificationException('Failed to send notification after $maxRetries retries.');
        }
    }

    private function validateTransaction(Transaction $transaction, array $wallets): void
    {
        if($transaction->getPayeer()->getRole() === self::STORE_USER){
            throw new UnauthorizedOperationException('User cannot perform this operation');
        }

        if($transaction->getAmount() > $wallets[$transaction->getPayeer()->getId()]->getAccountBalance()){
            throw new InsufficientBalanceException('Insufficient balance to carry out the operation');
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