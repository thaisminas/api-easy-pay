<?php

namespace App\Domain\Repository;


use App\Infra\Repository\Doctrine\CustomerEntity;

interface CustomerRepository
{
    public function save(CustomerEntity $user): void;
}