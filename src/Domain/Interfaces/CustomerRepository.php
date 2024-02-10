<?php

namespace App\Domain\Interfaces;

use App\Domain\Customer;
use App\Infra\Repository\Mappers\CustomerMapper;

interface CustomerRepository
{
    public function save(Customer $customer): CustomerMapper;
    public function findCustomerByPayeeAndPayeer(int $payeeId, int $payeerId): array;
    public function findById(int $customerId): Customer;
    public function findByDocument(string $document): Customer;
}