<?php

namespace App\Infra\Repository;

use App\Domain\Customer\Customer;
use App\Domain\Customer\Port\Inbound\CustomerRepository;
use App\Infra\Repository\Mappers\CustomerMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
}