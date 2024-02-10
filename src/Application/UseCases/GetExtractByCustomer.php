<?php

namespace App\Application\UseCases;

use App\Domain\Interfaces\TransactionRepository;

class GetExtractByCustomer
{
    private $trasactionRepository;
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->trasactionRepository = $transactionRepository;
    }

    public function execute(int $customerId): array
    {
        return $this->trasactionRepository->findExtractByCustomer($customerId);
    }

}