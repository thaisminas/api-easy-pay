<?php

namespace App\Application\Interfaces;

use App\Domain\Customer;
use App\Infra\Repository\Mappers\CustomerMapper;

interface CustomerInterface
{
    public function save(Customer $customer): CustomerMapper;
    public function findCustomerByPayeeAndPayeer(int $payeeId, int $payeerId): array;
    public function findById(int $customerId): Customer;
}