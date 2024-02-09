<?php

namespace App\Application\UseCases;

use App\Application\Factory\CustomerFactory;
use App\Domain\Port\Inbound\CustomerRepositoryPort;
use App\Infra\Repository\Mappers\CustomerMapper;

class CreateCustomer
{
    private $customerRepository;

    public function __construct(CustomerRepositoryPort $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function createCustomer(Array $customer): CustomerMapper
    {
        $customerEntity = CustomerFactory::createFromArray($customer);

        return $this->customerRepository->save($customerEntity);
    }
}