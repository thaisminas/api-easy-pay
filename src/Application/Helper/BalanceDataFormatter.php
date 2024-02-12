<?php

namespace App\Application\Helper;

use App\Domain\Transaction;

class BalanceDataFormatter
{
    public function format(array $wallets, Transaction $transaction): array
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
}
