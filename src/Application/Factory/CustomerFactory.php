<?php

namespace App\Application\Factory;

use App\Domain\Customer;
use App\Infra\Repository\Mappers\CustomerMapper;

class CustomerFactory
{
    public static function createFromArray(array $customerData): Customer
    {
        $customer = new Customer();
        $customer->setId($customer['customer'] ?? '');
        $customer->setName($customerData['name'] ?? '');
        $customer->setEmail($customerData['email'] ?? '');
        $customer->setSsn($customerData['ssn'] ?? '');
        $customer->setRole($customerData['role'] ?? '');
        $customer->setPassword($customerData['password'] ?? '');

        return $customer;
    }
}