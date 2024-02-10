<?php

namespace App\Infra\Repository;

use App\Application\Factory\TransactionFactory;
use App\Application\Interfaces\TransactionInterface;
use App\Domain\Transaction;
use App\Infra\Repository\Mappers\TransactionMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class TransactionDatabase extends ServiceEntityRepository implements TransactionInterface
{
    private $transactionFactory;
    public function __construct(ManagerRegistry $registry, TransactionFactory $transactionFactory)
    {
        parent::__construct($registry, TransactionMapper::class);
        $this->transactionFactory = $transactionFactory;
    }

    public function create(Transaction $transaction): void
    {
        try {
            $transactionMapper = $this->transactionFactory->toDatabaseEntity($transaction);

            $entityManager = $this->getEntityManager();
            $entityManager->persist($transactionMapper);
            $entityManager->flush();
        } catch (\Exception $e) {
            throw new RuntimeException('Error try saving transaction to database', 500, $e);
        }

    }

    public function findExtractByCustomer(int $customerId): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('t')
            ->from(TransactionMapper::class, 't')
            ->where('t.payee = :customerId OR t.payeer = :customerId')
            ->setParameter('customerId', $customerId);

        $extract = $queryBuilder->getQuery()->getResult();

        return $extract;
    }
}