<?php

namespace App\Application\UseCases;

use App\Application\Factory\TransactionFactory;
use App\Application\Factory\WalletFactory;
use App\Domain\Customer;
use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\Exception\UnauthorizedOperationException;
use App\Domain\Port\Inbound\CustomerRepositoryPort;
use App\Domain\Port\Inbound\NotificationClientPort;
use App\Domain\Port\Inbound\ServiceAuthorizationPort;
use App\Domain\Port\Inbound\TransactionRepositoryPort;
use App\Domain\Port\Inbound\WalletRepositoryPort;
use App\Domain\Transaction;
use App\Domain\Wallet;
use App\Infra\Repository\Mappers\WalletMapper;


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

    public function execute(Array $data): void
    {
        $customers = $this->customerRepository->findCustomerByPayeeAndPayeer($data['payeeId'], $data['payeerId']);
        $transaction = TransactionFactory::createFromArray($customers, $data);
        $wallets = $this->walletRepositoryPort->findCustomerByPayeeAndPayeer($transaction);

        try {
            $this->validateTransaction($transaction, $wallets);

            $situation = $this->serviceAuthorizationPort->getAuthorization();

            $status = $this->notificationClientPort->postNotification(json_encode([
                'message' => 'transfer sent!',
            ]));

            $this->amountToDeductBalance($wallets, $transaction);
            $this->trasactionRepository->create($transaction);

        } catch (\Error $err){
            $this->reverseAmountDeductedFromBalance($wallets, $transaction);

            throw new \Exception($err);
        }
    }

    private function validateTransaction(Transaction $transaction, array $wallets): void
    {
        if($transaction->getPayeer()->getRole() === 'PJ'){
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

        $this->walletRepositoryPort->updateAccountBalanceByCustomerId($balanceData);
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