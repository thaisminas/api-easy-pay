<?php

namespace App\Infra\Repository;

use App\Domain\Customer;
use App\Domain\Port\Inbound\TransactionRepositoryPort;
use App\Domain\Transaction;
use App\Infra\Repository\Mappers\CustomerMapper;
use App\Infra\Repository\Mappers\TransactionMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TransactionRepositoryPortDatabase extends ServiceEntityRepository implements TransactionRepositoryPort
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionMapper::class);
    }


    public function create(Transaction $transaction): void
    {
        $transactionEntityMapper = new TransactionMapper();
        $transactionEntityMapper->toDatabaseEntity($transaction);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($transactionEntityMapper);
        $entityManager->flush();
    }
}