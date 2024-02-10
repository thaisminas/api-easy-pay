<?php

namespace App\Infra\Repository;

use App\Application\Factory\CustomerFactory;
use App\Domain\Customer;
use App\Domain\Exception\NotFoundException;
use App\Domain\Interfaces\CustomerRepository;
use App\Infra\Repository\Mappers\CustomerMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepositoryDatabase extends ServiceEntityRepository implements CustomerRepository
{
    private $customerMapper;
    private $customerFactory;
    public function __construct(ManagerRegistry $registry, CustomerMapper $customerMapper, CustomerFactory $customerFactory)
    {
        parent::__construct($registry, CustomerMapper::class);
        $this->customerMapper = $customerMapper;
        $this->customerFactory = $customerFactory;
    }

    public function save(Customer $customer): CustomerMapper
    {
        $customerMapper = $this->customerFactory->toDatabase($customer);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($customerMapper);
        $entityManager->flush();

        return $customerMapper;
    }

    public function findCustomerByPayeeAndPayeer($payeeId, $payeerId): array
    {

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('customer')
            ->from(CustomerMapper::class, 'customer')
            ->where('customer.id = :payeeId OR customer.id = :payeerId')
            ->setParameter('payeeId', $payeeId)
            ->setParameter('payeerId', $payeerId);

        $query = $queryBuilder->getQuery();
        $customers = $query->getResult();

        $entities = [];

        foreach ($customers as $customer){
            $customer = $this->customerFactory->fromDatabase($customer);
            $entities[$customer->getId()] = $customer;
        }
        return $entities;
    }

    public function findById(int $customerId): Customer
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('customer')
            ->from(CustomerMapper::class, 'customer')
            ->where('customer.id = :id')
            ->setParameter('id', $customerId)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$customerId) {
            throw new NotFoundException('Customer not found');
        }

        $query = $queryBuilder->getQuery()->getSingleResult();

        return $this->customerFactory->fromDatabase($query);
    }
}