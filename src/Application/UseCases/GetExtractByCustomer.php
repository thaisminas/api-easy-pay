<?php

namespace App\Application\UseCases;

use App\Application\Interfaces\TransactionInterface;

class GetExtractByCustomer
{
    private $trasactionInterface;
    public function __construct(TransactionInterface $transactionInterface)
    {
        $this->trasactionInterface = $transactionInterface;
    }

    public function execute(int $customerId): array
    {
        return $this->trasactionInterface->findExtractByCustomer($customerId);
    }

}