<?php

namespace App\Infra\Repository;

use App\Domain\Customer;
use App\Domain\Port\Inbound\CustomerRepositoryPort;
use App\Infra\Repository\Mappers\CustomerMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerRepositoryPortDatabase extends ServiceEntityRepository implements CustomerRepositoryPort
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

        foreach ($customers as $customer){
            $customerEntity = new Customer();
            $customerEntity->setId($customer->id);
            $customerEntity->setName($customer->name);
            $customerEntity->setEmail($customer->email);
            $customerEntity->setDocument($customer->document);
            $customerEntity->setRole($customer->role);

            $entities[$customerEntity->getId()] = $customerEntity;
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

        $query = $qb->getQuery()->getSingleResult();

        $customerEntity = new CustomerMapper();
        return $customerEntity->fromDatabase($query);
    }
}