<?php

namespace App\Domain\Customer\Port\Inbound;

use App\Domain\Customer\Customer;
use App\Infra\Repository\Mappers\CustomerMapper;

interface CustomerRepository
{
    public function save(Customer $customer): CustomerMapper;
}