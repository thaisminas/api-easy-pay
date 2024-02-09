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
        $customer->setDocument($customerData['document']);
        $customer->setRole($customerData['role']);

        return $customer;
    }
}