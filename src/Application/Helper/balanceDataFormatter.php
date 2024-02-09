<?php

use App\Domain\Transaction;

function balanceDataFormatter(array $wallets, Transaction $transaction)
{
    return [
        'payee' => [
            'id' => $transaction->getPayeer()->getId(),
            'amount' => $wallets[$transaction->getPayeer()->getId()]->getAccountBalance()
        ],
        'payeer' => [
            'id' => $transaction->getPayee()->getId(),
            'amount' => $wallets[$transaction->getPayee()->getId()]->getAccountBalance(),
        ]
    ];
}