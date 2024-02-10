<?php

namespace App\Application\UseCases;

use App\Application\Factory\CustomerFactory;
use App\Application\Interfaces\CustomerInterface;
use App\Domain\Exception\DocumentInvalidException;
use App\Domain\Exception\InvalidRoleException;
use App\Infra\Repository\Mappers\CustomerMapper;
use Exception;


class CreateCustomer
{
    const COMMON = 'COMMON';
    const STORE_USER = 'STORE_USER';
    private $customerInterface;
    private $customerFactory;

    public function __construct(CustomerInterface $customerInterface, CustomerFactory $customerFactory)
    {
        $this->customerInterface = $customerInterface;
        $this->customerFactory = $customerFactory;
    }

    public function execute(array $customer): CustomerMapper
    {
        $this->validateCustomer($customer);

        $customer['document'] = validateDocument($customer);
        $customerEntity = $this->customerFactory->createFromArray($customer);

        return $this->customerInterface->save($customerEntity);
    }

    private function validateCustomer(array $customer)
    {
        $role = strtoupper($customer['role']);
        $document = $customer['document'];

        if($role !== self::COMMON && $role !== self::STORE_USER){
            throw new InvalidRoleException();
        }

        if(strlen($customer['name']) < 3){
            throw new Exception('Name is Invalid', 422);
        }

        if($document === null || strlen($document) < 11){
            throw new DocumentInvalidException('Document is invalid');
        }
    }
}