<?php

namespace App\Infra\Repository;

use App\Domain\Transaction\Port\Inbound\TransactionRepository;
use App\Domain\Transaction\Transaction;
use App\Infra\Repository\Mappers\TransactionMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TransactionRepositoryDatabase extends ServiceEntityRepository implements TransactionRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }


    public function create(Transaction $transaction): void
    {
        $transactionEntityMapper = new TransactionMapper();
        $transactionEntityMapper->toDatabaseEntity($transaction);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($transactionEntityMapper);
        $entityManager->flush();
    }

    public function findCustomerByPayeeAndPayeer($payeeId, $payeerId): array
    {

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('customer')
            ->from('App\Infra\Repository\Mappers\Customer', 'customer')
            ->where('customer.id = :payeeId OR customer.id = :payeerId')
            ->setParameter('payeeId', $payeeId)
            ->setParameter('payeerId', $payeerId);

        $query = $qb->getQuery();
        $customers = $query->getResult();

        $customerEntities = [];

        $transactionEntityMapper = new Transaction();
        foreach ($customers as $customer){
            $customerEntities[] = $transactionEntityMapper->fromDatabaseEntity($customer);
        }

        return $customerEntities;
    }
}