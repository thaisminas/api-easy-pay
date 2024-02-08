<?php

namespace App\Infra\Repository;

use App\Domain\Customer\Customer;
use App\Domain\Transaction\Port\Inbound\TransactionRepository;
use App\Domain\Transaction\Transaction;
use App\Infra\Repository\Mappers\TransactionMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

class TransactionRepositoryDatabase extends ServiceEntityRepository implements TransactionRepository
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

    public function findCustomerByPayeeAndPayeer($payeeId, $payeerId): array
    {

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('customer')
            ->from('App\Infra\Repository\Mappers\CustomerMapper', 'customer')
            ->where('customer.id = :payeeId OR customer.id = :payeerId')
            ->setParameter('payeeId', $payeeId)
            ->setParameter('payeerId', $payeerId);

        $query = $qb->getQuery();
        $customers = $query->getResult();

        $entities = [];

        foreach ($customers as $customer){
            $customerEntity = new Customer();
            $customerEntity->setId($customer->id);
            $customerEntity->setName($customer->name);
            $customerEntity->setEmail($customer->email);
            $customerEntity->setSsn($customer->ssn);
            $customerEntity->setRole($customer->role);
            $customerEntity->setPassword($customer->password);
            $customerEntity->setCreatedAt($customer->createdAt);
            $customerEntity->setUpdatedAt($customer->updatedAt);

            $entities[$customerEntity->getId()] = $customerEntity;
        }

        return $entities;
    }
}