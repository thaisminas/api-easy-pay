<?php

namespace App\Domain\Customer\UseCases;

use App\Domain\Customer\Customer;
use App\Domain\Customer\Port\Inbound\CustomerRepository;
use App\Infra\Repository\Mappers\CustomerMapper;

class CreateCustomer
{
    private $customerRepository;

    public function __construct(CustomerRepository $userRepository)
    {
        $this->customerRepository = $userRepository;
    }

    public function createCustomer(Array $customer): CustomerMapper
    {
        $customerEntity = new Customer();
        $customerEntity->setName($customer['name']);
        $customerEntity->setEmail($customer['email']);
        $customerEntity->setSsn($customer['ssn']);
        $customerEntity->setRole($customer['role']);
        $customerEntity->setPassword($customer['password']);

        return $this->customerRepository->save($customerEntity);
    }
}