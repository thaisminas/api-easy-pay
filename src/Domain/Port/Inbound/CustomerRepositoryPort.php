<?php

namespace App\Domain\Port\Inbound;

use App\Domain\Customer;
use App\Infra\Repository\Mappers\CustomerMapper;

interface CustomerRepositoryPort
{
    public function save(Customer $customer): CustomerMapper;
    public function findCustomerByPayeeAndPayeer(int $payeeId, int $payeerId): array;
    public function findById(array $operation): Customer;
}