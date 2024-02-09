<?php

namespace App\Application\Factory;

use App\Domain\Customer;

class CustomerFactory
{
    public static function createFromArray(array $customerData): Customer
    {
        $customer = new Customer();
        isset($customerData['id']) ? $customer->setId($customerData['id']) : null;

        $customer->setName($customerData['name']);
        $customer->setEmail($customerData['email'] );
        $customer->setSsn($customerData['ssn']);
        $customer->setRole($customerData['role']);
        $customer->setPassword($customerData['password']);

        return $customer;
    }
}