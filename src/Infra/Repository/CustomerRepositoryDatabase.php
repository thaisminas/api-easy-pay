<?php

namespace App\Infra\Repository;

use App\Domain\Customer;
use App\Domain\Interfaces\CustomerRepository;
use App\Infra\Repository\Mappers\CustomerMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerRepositoryDatabase extends ServiceEntityRepository implements CustomerRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerMapper::class);
    }

    public function save(Customer $customer): CustomerMapper
    {
        $customerMapper = new CustomerMapper();
        $customerMapper->toDatabase($customer);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($customerMapper);
        $entityManager->flush();

        return $customerMapper;
    }

    public function findCustomerByPayeeAndPayeer($payeeId, $payeerId): array
    {

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('customer')
            ->from(CustomerMapper::class, 'customer')
            ->where('customer.id = :payeeId OR customer.id = :payeerId')
            ->setParameter('payeeId', $payeeId)
            ->setParameter('payeerId', $payeerId);

        $query = $qb->getQuery();
        $customers = $query->getResult();

        $entities = [];

        $customerMapper = new CustomerMapper();

        foreach ($customers as $customer){
            $customer = $customerMapper->fromDatabase($customer);
            $entities[$customer->getId()] = $customer;
        }
        return $entities;
    }

    public function findById(int $customerId): Customer
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('customer')
            ->from(CustomerMapper::class, 'customer')
            ->where('customer.id = :id')
            ->setParameter('id', $customerId)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$customerId) {
            throw new NotFoundHttpException('Customer not found');
        }

        $query = $qb->getQuery()->getSingleResult();

        $customerEntity = new CustomerMapper();
        return $customerEntity->fromDatabase($query);
    }

    public function findByDocument(string $document): Customer
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('customer')
            ->from(CustomerMapper::class, 'customer')
            ->where('customer.document = :id')
            ->setParameter('id', $document)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$document) {
            throw new NotFoundHttpException('Customer not found');
        }

        $query = $qb->getQuery()->getSingleResult() ?? null;

        $customerEntity = new CustomerMapper();
        return $customerEntity->fromDatabase($query);
    }
}