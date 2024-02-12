<?php

namespace App\Application\Factory;

use App\Domain\Customer;
use App\Infra\Repository\Mappers\CustomerMapper;

class CustomerFactory
{
    public function createFromArray(array $customerData): Customer
    {
        $customer = new Customer();
        isset($customerData['id']) ? $customer->setId($customerData['id']) : null;

        $customer->setName($customerData['name']);
        $customer->setEmail($customerData['email'] );
        $customer->setDocument($customerData['document']);
        $customer->setRole(strtoupper($customerData['role']));
        $customer->setPassword($customerData['password']);

        return $customer;
    }

    public function toDatabase(Customer $customer): CustomerMapper
    {
        $customerMapper = new CustomerMapper();
        $customerMapper->name = $customer->getName();
        $customerMapper->document = $customer->getDocument();
        $customerMapper->email = $customer->getEmail();
        $customerMapper->role = $customer->getRole();
        $customerMapper->password = $customer->getPassword();

        return $customerMapper;
    }

    public function fromDatabase(CustomerMapper $customerMapper): Customer
    {
        $customer = new Customer();
        $customer->setId($customerMapper->id);
        $customer->setName($customerMapper->name);
        $customer->setEmail($customerMapper->email);
        $customer->setDocument($customerMapper->document);
        $customer->setRole($customerMapper->role);
        $customer->setPassword($customerMapper->password);

        return $customer;
    }
}